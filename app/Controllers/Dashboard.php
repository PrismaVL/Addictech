<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }

        if ($session->get('role') !== 'admin') {
            return redirect()->to('home')->with('error', 'You do not have permission to access the admin dashboard.');
        }

        $productsModel = model('Products_model');
        $ordersModel = model('Orders_model');
        $usersModel = model('Users_model');
        $contactsModel = model('Contacts_model');

        $recentOrders = model('Orders_model')
            ->select('orders.id, orders.order_number, orders.status, orders.total, orders.created_at, CONCAT(users.first_name, " ", users.last_name) AS customer_name')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll(5);

        $lowStockProducts = model('Products_model')
            ->where('status', 'active')
            ->where('stock <=', 5)
            ->orderBy('stock', 'ASC')
            ->findAll(5);

        $unreadContacts = model('Contacts_model')
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll(4);

        return view('view_admin_dashboard', [
            'title' => 'Admin Overview',
            'nav_active' => 'dashboard',
            'totalProducts' => $productsModel->countAllResults(),
            'totalOrders' => $ordersModel->countAllResults(),
            'pendingOrders' => model('Orders_model')->where('status', 'pending')->countAllResults(),
            'totalUsers' => $usersModel->countAllResults(),
            'unreadConcerns' => model('Contacts_model')->where('is_read', 0)->countAllResults(),
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'unreadContacts' => $unreadContacts,
        ]);
    }
}
