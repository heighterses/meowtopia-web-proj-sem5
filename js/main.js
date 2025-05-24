// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
  // Mobile menu setup
  const mobileMenuButton = document.querySelector('.mobile-menu-button');
  const headerMiddleSection = document.querySelector('.header-middle-section');
  
  if (mobileMenuButton && headerMiddleSection) {
    mobileMenuButton.addEventListener('click', function() {
      headerMiddleSection.classList.toggle('active');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!event.target.closest('.header-middle-section') && 
          !event.target.closest('.mobile-menu-button')) {
        headerMiddleSection.classList.remove('active');
      }
    });
  }

  // Set active nav link based on current page
  const currentPage = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    if (currentPage.includes(link.getAttribute('href'))) {
      link.classList.add('active');
    }
  });

  // Shopping cart functionality (only for checkout page)
  if (window.location.pathname.includes('checkOut.html')) {
    const minusButtons = document.querySelectorAll('.minus');
    const plusButtons = document.querySelectorAll('.plus');
    const removeButtons = document.querySelectorAll('.remove-item');
    const continueShoppingBtn = document.querySelector('.continue-shopping');

    // Update quantity function
    function updateQuantity(input, change) {
      let value = parseInt(input.value) + change;
      value = Math.max(1, value); // Ensure minimum quantity is 1
      input.value = value;
      
      const price = parseFloat(input.closest('.cart-item').querySelector('.item-price').textContent.replace('$', ''));
      const total = (price * value).toFixed(2);
      input.closest('.cart-item').querySelector('.item-total').textContent = `$${total}`;
      
      updateCartTotal();
    }

    // Update cart total
    function updateCartTotal() {
      const itemTotals = Array.from(document.querySelectorAll('.item-total'))
        .map(el => parseFloat(el.textContent.replace('$', '')));
      const subtotal = itemTotals.reduce((sum, total) => sum + total, 0);
      const shipping = 5.99;
      const tax = (subtotal * 0.1075).toFixed(2);
      const total = (subtotal + shipping + parseFloat(tax)).toFixed(2);

      document.querySelector('.summary-item:nth-child(1) span:last-child').textContent = `$${subtotal.toFixed(2)}`;
      document.querySelector('.summary-item:nth-child(3) span:last-child').textContent = `$${tax}`;
      document.querySelector('.summary-total span:last-child').textContent = `$${total}`;
    }

    // Event listeners for quantity buttons
    minusButtons.forEach(button => {
      button.addEventListener('click', () => {
        const input = button.nextElementSibling;
        updateQuantity(input, -1);
      });
    });

    plusButtons.forEach(button => {
      button.addEventListener('click', () => {
        const input = button.previousElementSibling;
        updateQuantity(input, 1);
      });
    });

    // Event listeners for remove buttons
    removeButtons.forEach(button => {
      button.addEventListener('click', () => {
        button.closest('.cart-item').remove();
        updateCartTotal();
      });
    });

    // Continue shopping button
    if (continueShoppingBtn) {
      continueShoppingBtn.addEventListener('click', () => {
        window.location.href = './shop.html';
      });
    }

    // Checkout button
    const checkoutButton = document.querySelector('.checkout-button');
    if (checkoutButton) {
      checkoutButton.addEventListener('click', () => {
        alert('Thank you for your order! This is a demo page.');
      });
    }
  }
}); 