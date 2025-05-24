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

  // Initialize cart from localStorage or create empty cart
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  // Update cart count on page load
  updateCartCount();

  // Add to cart functionality (for shop page)
  if (window.location.pathname.includes('shop.html')) {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        const productPrice = parseFloat(this.dataset.productPrice);
        
        // Check if product already exists in cart
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
          existingItem.quantity += 1;
        } else {
          cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            quantity: 1
          });
        }
        
        // Save cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart count
        updateCartCount();
        
        // Show notification
        showCartNotification(`${productName} added to cart!`);
      });
    });
  }

  // Shopping cart functionality (for checkout page)
  if (window.location.pathname.includes('checkOut.html')) {
    loadCartItems();
  }

  // Function to load cart items
  function loadCartItems() {
    const cartItemsContainer = document.querySelector('.cart-items');
    if (!cartItemsContainer) return;

    // Clear existing items
    cartItemsContainer.innerHTML = '';
    
    if (cart.length === 0) {
      cartItemsContainer.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
      updateCartTotal();
      return;
    }

    // Add items to cart
    cart.forEach(item => {
      const cartItemHTML = `
        <div class="cart-item" data-product-id="${item.id}">
          <div class="item-details">
            <h3>${item.name}</h3>
            <p class="item-description">Quantity: ${item.quantity}</p>
            <div class="item-price">$${item.price.toFixed(2)}</div>
          </div>
          <div class="item-quantity">
            <button class="quantity-btn minus" data-product-id="${item.id}">-</button>
            <input type="number" value="${item.quantity}" min="1" max="10" class="quantity-input" data-product-id="${item.id}">
            <button class="quantity-btn plus" data-product-id="${item.id}">+</button>
          </div>
          <div class="item-total">$${(item.price * item.quantity).toFixed(2)}</div>
          <button class="remove-item" data-product-id="${item.id}">×</button>
        </div>
      `;
      cartItemsContainer.insertAdjacentHTML('beforeend', cartItemHTML);
    });

    // Add event listeners for cart controls
    setupCartEventListeners();
    
    // Update cart total
    updateCartTotal();
  }

  // Function to setup cart event listeners
  function setupCartEventListeners() {
    // Minus buttons
    document.querySelectorAll('.quantity-btn.minus').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
        if (input) {
          updateQuantity(input, -1);
        }
      });
    });

    // Plus buttons
    document.querySelectorAll('.quantity-btn.plus').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
        if (input) {
          updateQuantity(input, 1);
        }
      });
    });

    // Remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        removeCartItem(productId);
      });
    });

    // Continue shopping button
    const continueShoppingBtn = document.querySelector('.continue-shopping');
    if (continueShoppingBtn) {
      continueShoppingBtn.addEventListener('click', () => {
        window.location.href = '../pages/shop.html';
      });
    }

    // Checkout button
    const checkoutButton = document.querySelector('.checkout-button');
    if (checkoutButton) {
      checkoutButton.addEventListener('click', () => {
        if (cart.length === 0) {
          alert('Your cart is empty!');
          return;
        }
        alert('This is a dummy cart - no actual purchase will be made!');
        // Clear cart after successful order
        cart = [];
        localStorage.removeItem('cart');
        updateCartCount();
        loadCartItems();
      });
    }
  }

  // Function to update quantity
  function updateQuantity(input, change) {
    const productId = input.dataset.productId;
    let value = parseInt(input.value) + change;
    value = Math.max(1, Math.min(10, value)); // Ensure quantity is between 1 and 10
    input.value = value;
    
    // Update cart item
    const cartItemIndex = cart.findIndex(item => item.id === productId);
    if (cartItemIndex !== -1) {
      cart[cartItemIndex].quantity = value;
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Update cart item total
      const cartItem = input.closest('.cart-item');
      const price = parseFloat(cartItem.querySelector('.item-price').textContent.replace('$', ''));
      cartItem.querySelector('.item-total').textContent = `$${(price * value).toFixed(2)}`;
      
      // Update cart count and total
      updateCartCount();
      updateCartTotal();
    }
  }

  // Function to remove cart item
  function removeCartItem(productId) {
    const cartItemIndex = cart.findIndex(item => item.id === productId);
    if (cartItemIndex !== -1) {
      cart.splice(cartItemIndex, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartCount();
      loadCartItems(); // Reload cart items to update the view
    }
  }

  // Function to update cart total
  function updateCartTotal() {
    // Calculate subtotal from cart items
    const subtotal = cart.length === 0 ? 0 : cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = cart.length === 0 ? 0 : 5.99;  // Only add shipping if there are items
    const tax = cart.length === 0 ? 0 : (subtotal * 0.1075);  // Only calculate tax if there are items
    const total = cart.length === 0 ? 0 : (subtotal + shipping + tax);  // Total should be 0 if cart is empty

    const subtotalElement = document.querySelector('.summary-item:nth-child(1) span:last-child');
    const shippingElement = document.querySelector('.summary-item:nth-child(2) span:last-child');
    const taxElement = document.querySelector('.summary-item:nth-child(3) span:last-child');
    const totalElement = document.querySelector('.summary-total span:last-child');

    // Update all summary elements with proper formatting
    if (subtotalElement) subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    if (shippingElement) shippingElement.textContent = `$${shipping.toFixed(2)}`;
    if (taxElement) taxElement.textContent = `$${tax.toFixed(2)}`;
    if (totalElement) totalElement.textContent = `$${total.toFixed(2)}`;
  }

  // Helper function to update cart count in header
  function updateCartCount() {
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    const cartQuantityElements = document.querySelectorAll('.cart-quantity');
    cartQuantityElements.forEach(element => {
      element.textContent = cartCount;
    });
  }

  // Helper function to show cart notification
  function showCartNotification(message) {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.cart-notification');
    if (existingNotification) {
      existingNotification.remove();
    }

    // Create and show new notification
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    notification.style.display = 'block';

    // Remove notification after 3 seconds
    setTimeout(() => {
      notification.style.display = 'none';
      notification.remove();
    }, 3000);
  }
}); 