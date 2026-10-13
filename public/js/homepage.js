  document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior to all anchor links
    const allLinks = document.querySelectorAll('a[href^="#"]');
    allLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
          targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Check if URL has hash on page load and scroll to it
    if (window.location.hash) {
      const targetElement = document.querySelector(window.location.hash);
      if (targetElement) {
        setTimeout(() => {
          targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }, 100);
      }
    }

    const carousel = document.querySelector('.bs-carousel');
    const track = carousel?.querySelector('.bs-grid');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    if (carousel && track && track.children.length > 1 && !reducedMotion.matches) {
      const cards = Array.from(track.children);
      const clones = cards.map(card => {
        const clone = card.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        clone.querySelectorAll('a, button, input, select, textarea, [tabindex]').forEach(element => {
          element.tabIndex = -1;
        });
        return clone;
      });
      track.append(...clones);

      const pauseReasons = new Set();
      const pixelsPerSecond = 30;
      const speedTransitionDuration = 1000;
      let offset = 0;
      let speed = pixelsPerSecond;
      let transitionFromSpeed = pixelsPerSecond;
      let transitionToSpeed = pixelsPerSecond;
      let speedTransitionStart;
      let loopDistance = 0;
      let frameId;
      let lastFrameTime;

      const measureLoop = () => {
        const first = cards[0].getBoundingClientRect();
        const last = cards[cards.length - 1].getBoundingClientRect();
        loopDistance = last.right - first.left;
      };

      const setTargetSpeed = target => {
        if (transitionToSpeed === target) return;
        transitionFromSpeed = speed;
        transitionToSpeed = target;
        speedTransitionStart = performance.now();
      };

      const stop = reason => {
        const wasPaused = pauseReasons.size > 0;
        pauseReasons.add(reason);
        if (!wasPaused) setTargetSpeed(0);
        if (!frameId && !document.hidden) frameId = requestAnimationFrame(animate);
      };

      const animate = timestamp => {
        if (document.hidden) {
          frameId = undefined;
          lastFrameTime = undefined;
          return;
        }

        const elapsed = lastFrameTime === undefined
          ? 0
          : Math.min(timestamp - lastFrameTime, 50);
        if (speedTransitionStart !== undefined) {
          const progress = Math.min(
            (timestamp - speedTransitionStart) / speedTransitionDuration,
            1
          );
          const easedProgress = progress * progress * progress
            * (progress * (progress * 6 - 15) + 10);
          speed = transitionFromSpeed + (transitionToSpeed - transitionFromSpeed) * easedProgress;
          if (progress === 1) {
            speed = transitionToSpeed;
            speedTransitionStart = undefined;
          }
        }

        if (elapsed > 0 && speed > 0) {
          offset += (elapsed / 1000) * speed;
          if (loopDistance > 0) offset %= loopDistance;
          track.style.transform = `translate3d(-${offset}px, 0, 0)`;
        }
        lastFrameTime = timestamp;

        if (speedTransitionStart === undefined && speed === 0) {
          frameId = undefined;
          lastFrameTime = undefined;
          return;
        }

        frameId = requestAnimationFrame(animate);
      };

      const start = reason => {
        pauseReasons.delete(reason);
        if (pauseReasons.size === 0) setTargetSpeed(pixelsPerSecond);
        if (!document.hidden && !frameId) {
          frameId = requestAnimationFrame(animate);
        }
      };

      measureLoop();
      window.addEventListener('resize', measureLoop);

      carousel.addEventListener('pointerenter', () => stop('pointer'));
      carousel.addEventListener('pointerleave', () => start('pointer'));
      carousel.addEventListener('focusin', () => stop('focus'));
      carousel.addEventListener('focusout', event => {
        if (!carousel.contains(event.relatedTarget)) start('focus');
      });
      carousel.addEventListener('pointerdown', () => stop('hold'));
      window.addEventListener('pointerup', () => start('hold'));
      window.addEventListener('pointercancel', () => start('hold'));
      document.addEventListener('visibilitychange', () => {
        if (document.hidden) stop('hidden');
        else start('hidden');
      });

      start('init');
    }

    const featuredDataElement = document.getElementById('featuredProductsData');
    const featuredBackdrop = document.getElementById('featuredModalBackdrop');
    const featuredModal = document.getElementById('featuredProductModal');
    const featuredTrack = document.querySelector('.bs-grid');
    if (featuredDataElement && featuredBackdrop && featuredModal && featuredTrack) {
      const featuredData = JSON.parse(featuredDataElement.textContent);
      const productsById = new Map(featuredData.products.map(product => [product.id, product]));
      const wishlistIds = new Set(featuredData.wishlistedIds);
      const toast = document.getElementById('featuredModalToast');
      const modalForm = document.getElementById('featuredModalForm');
      const csrfInput = Array.from(modalForm.elements).find(input =>
        input.type === 'hidden' && input.name !== 'product_id' && input.name !== 'quantity'
      );
      let currentProduct;
      let quantity = 1;
      let returnFocus;
      let toastTimer;

      const showToast = message => {
        toast.textContent = message;
        toast.classList.add('show');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(() => toast.classList.remove('show'), 2200);
      };

      const updateWishlistButton = () => {
        const wished = wishlistIds.has(currentProduct.id);
        const button = document.getElementById('featuredModalWishlist');
        const icon = document.getElementById('featuredWishIcon');
        button.classList.toggle('modal-wish-active', wished);
        button.setAttribute('aria-label', wished ? 'Remove from wishlist' : 'Add to wishlist');
        document.getElementById('featuredWishLabel').textContent = wished ? 'WISHLISTED' : 'WISHLIST';
        icon.setAttribute('fill', wished ? '#e05252' : 'none');
        icon.setAttribute('stroke', wished ? '#e05252' : 'currentColor');
      };

      const openFeaturedModal = (product, opener) => {
        currentProduct = product;
        returnFocus = opener;
        quantity = 1;
        const image = document.getElementById('featuredModalImage');
        image.src = product.image
          ? `${featuredBackdrop.dataset.imageBase}${encodeURIComponent(product.image)}`
          : '';
        image.alt = product.name;
        image.hidden = !product.image;
        document.getElementById('featuredModalName').textContent = product.name;
        document.getElementById('featuredModalDescription').textContent = product.description || 'No description available.';
        document.getElementById('featuredModalPrice').textContent = `₱${Number(product.price).toLocaleString('en-PH', { maximumFractionDigits: 0 })}`;
        document.getElementById('featuredFormProductId').value = product.id;
        document.getElementById('featuredQtyValue').textContent = quantity;
        document.getElementById('featuredFormQuantity').value = quantity;
        updateWishlistButton();
        featuredBackdrop.classList.add('open');
        featuredBackdrop.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.getElementById('featuredModalClose').focus();
      };

      const closeFeaturedModal = () => {
        featuredBackdrop.classList.remove('open');
        featuredBackdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (returnFocus?.isConnected) returnFocus.focus();
      };

      const updateQuantity = next => {
        quantity = Math.max(1, Math.min(currentProduct.stock, next));
        document.getElementById('featuredQtyValue').textContent = quantity;
        document.getElementById('featuredFormQuantity').value = quantity;
      };

      featuredTrack.addEventListener('click', event => {
        const card = event.target.closest('.bs-card[data-product-id]');
        if (!card) return;
        const product = productsById.get(Number(card.dataset.productId));
        if (!product) return;
        event.preventDefault();
        openFeaturedModal(product, card);
      });

      featuredTrack.addEventListener('keydown', event => {
        if (event.key !== 'Enter') return;
        const card = event.target.closest('.bs-card[data-product-id]');
        if (!card) return;
        const product = productsById.get(Number(card.dataset.productId));
        if (!product) return;
        event.preventDefault();
        openFeaturedModal(product, card);
      });

      document.getElementById('featuredModalClose').addEventListener('click', closeFeaturedModal);
      featuredBackdrop.addEventListener('click', event => {
        if (event.target === featuredBackdrop) closeFeaturedModal();
      });
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && featuredBackdrop.classList.contains('open')) {
          closeFeaturedModal();
        }
      });
      document.getElementById('featuredQtyMinus').addEventListener('click', () => updateQuantity(quantity - 1));
      document.getElementById('featuredQtyPlus').addEventListener('click', () => updateQuantity(quantity + 1));

      document.getElementById('featuredModalWishlist').addEventListener('click', async () => {
        if (!csrfInput) {
          showToast('Could not update wishlist.');
          return;
        }

        try {
          const response = await fetch(featuredBackdrop.dataset.wishlistUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
              product_id: currentProduct.id,
              [csrfInput.name]: csrfInput.value
            })
          });
          if (!response.ok) throw new Error(`Wishlist request failed: ${response.status}`);
          const result = await response.json();
          if (result.redirect) {
            window.location.assign(result.redirect);
            return;
          }
          if (!result.success) {
            showToast('Could not update wishlist.');
            return;
          }
          if (result.wished) wishlistIds.add(currentProduct.id);
          else wishlistIds.delete(currentProduct.id);
          updateWishlistButton();
          showToast(result.wished ? 'Added to wishlist.' : 'Removed from wishlist.');
        } catch (error) {
          console.error('Featured product wishlist update failed.', error);
          showToast('Could not update wishlist.');
        }
      });

      document.getElementById('featuredModalShare').addEventListener('click', async () => {
        const shareData = { title: currentProduct.name, url: window.location.href };
        try {
          if (navigator.share) await navigator.share(shareData);
          else if (navigator.clipboard) {
            await navigator.clipboard.writeText(shareData.url);
            showToast('Link copied.');
          } else {
            showToast('Sharing is not supported in this browser.');
          }
        } catch (error) {
          if (error.name !== 'AbortError') {
            console.error('Featured product share failed.', error);
            showToast('Could not share this product.');
          }
        }
      });
    }

    const promoPanel = document.querySelector('.promo-panel');
    if (promoPanel && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      const promoObserver = new IntersectionObserver(([entry]) => {
        promoPanel.classList.toggle('is-centered', entry.isIntersecting);
      }, {
        rootMargin: '-35% 0px -35% 0px'
      });

      promoObserver.observe(promoPanel);
    }
  });