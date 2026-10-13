<?php

namespace App\Controllers;

class Users extends BaseController
{
    public function index() //view all users on admin dashboard
    {   
        // $session = session();
        // $checkses = $session->get('isLoggedIn');
        // if (!$checkses) {
        // return redirect()->to('/login')->with('error', 'Please login first.');
        // }

        $usermodel = model('Users_model');

        $data = array(
            'title' => 'TW32 App - View User Record',
            'users' => $usermodel->findAll()
        );

        return view('view_admin_user', $data);
    }

    public function profile() {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }

        $user_id     = $session->get('user_id');
        $ordersModel = model('Orders_model');
        $orders      = $ordersModel->getOrdersByUserWithItems($user_id);

        $data = $session->get();
        $data['orders'] = $orders;

        return view('view_user_profile', ['user' => $data, 'orders' => $orders]);
    }

    public function view($id) {  //single user view from admin dashboard
        $usermodel = model('Users_model');

        $data = array(
            'title' => 'TW32 App - View User Record',
            'user' => $usermodel->find($id)
        );

        return view('view_adminView_user', $data);
    }

    public function edit($id) { //FOR FORM
        $usermodel = model('Users_model');
        $session = session();

        $data = array(
            'title' => 'TW32 App - Edit User Record',
            'user' => $usermodel->find($id)
        );

        return view('view_adminEdit_user', $data);
    }

    //ACTUAL UPDATE
    public function update($id) { //TODO: validation for inputs and flashdata set
        $usermodel = model('Users_model');
        $session = session();
        $user = $usermodel->find($id);
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found.');
        }

        $redirectTo = $this->request->getPost('redirect_to') ?? 'user/profile';
        $postData = $this->request->getPost();
        $profileFields = [
            'first_name',
            'last_name',
            'country',
            'city',
            'postal_code',
            'address',
            'phone',
        ];
        $data = [];

        foreach ($profileFields as $field) {
            if (array_key_exists($field, $postData)) {
                $data[$field] = $postData[$field];
            }
        }

        $isEditingOwnProfile = (string) $session->get('user_id') === (string) $id;
        if ($isEditingOwnProfile) {
            $data['country'] = 'Philippines';
        }

        foreach (['first_name', 'last_name', 'city', 'country', 'address'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = preg_replace_callback(
                    '/^(\s*)(\S)/u',
                    static fn(array $match): string => $match[1] . mb_strtoupper($match[2], 'UTF-8'),
                    $data[$field],
                    1
                );
            }
        }

        if (
            $isEditingOwnProfile
            && isset($data['phone'])
            && trim((string) $data['phone']) !== ''
        ) {
            $digits = preg_replace('/\D+/', '', (string) $data['phone']);
            if (str_starts_with($digits, '63')) {
                $digits = substr($digits, 2);
            }
            if (str_starts_with($digits, '9')) {
                $digits = '0' . $digits;
            } elseif (str_starts_with($digits, '0') && !str_starts_with($digits, '09')) {
                $digits = '09' . substr($digits, 1);
            } elseif (!str_starts_with($digits, '09')) {
                $digits = '09' . $digits;
            }

            if (!preg_match('/^09\d{9}$/', $digits)) {
                return redirect()
                    ->to($redirectTo)
                    ->with('error', 'Enter an 11-digit Philippine mobile number starting with 09.')
                    ->withInput();
            }

            $data['phone'] = $digits;
        }

        if (!$isEditingOwnProfile) {
            foreach (['role', 'status'] as $field) {
                if (array_key_exists($field, $postData)) {
                    $data[$field] = $postData[$field];
                }
            }
        }

        if (!empty($data)) {
            $usermodel->update($id, $data);
        }

        if ($isEditingOwnProfile) {
            $session->set(array_intersect_key($data, array_flip($profileFields)));
        }

        $session->setFlashData('success', 'User updated successfully.');
        return redirect()->to($redirectTo);
    }

    public function delete($id) { //modal for sureness
        $usermodel = model('Users_model');
        $session = session();
        try {
        $usermodel->delete($id);

        $session->setFlashdata('success', 'User deleted successfully.');
        return redirect()->to('admin/users');

    } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
        $session->setFlashdata('error', 'Cannot delete this user because they have existing orders.');
        return redirect()->to('admin/users');
        }
    }

    public function deactview(){
        return view('deact_view');
    }

    public function statuschangeview(){
        $usermodel = model('Users_model');


        $data['users'] = $usermodel->findAll();

        return view('user_status', $data);
    }

}
