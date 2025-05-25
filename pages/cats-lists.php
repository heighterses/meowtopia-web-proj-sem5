<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/cat_operations.php';

$cats = getAllCats();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adopt me</title>
    <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../Style/header-css/header.css" />
    <link rel="stylesheet" href="../Style/cat-list-page-css/cats-grid.css" />
    <style>
      .admin-section {
        text-align: center;
        margin: 20px 0;
      }
      .add-cat-button {
        display: inline-block;
        background-color: #d23449;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
      }
      .add-cat-button:hover {
        background-color: #b82a3d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(210, 52, 73, 0.2);
      }
      .admin-login-link {
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
      }
      .admin-login-link:hover {
        color: #d23449;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <div class="header-left-section">
        <a class="header-link" href="../index.html">
          <img class="meowtopia-logo" src="../src/icons/mainIcon.ico" />
        </a>
        <div class="meowtopia-name">MeowTopia</div>
      </div>

      <div class="header-middle-section">
        <a href="./cats-lists.php" class="nav-link">Adoption Page</a>
        <a href="./stories.html" class="nav-link">Cat rescue tales</a>
        <a href="./shop.html" class="nav-link">Cat foods and toys</a>
        <a href="./services.html" class="nav-link">Our Services</a>
        <a href="./contact.html" class="nav-link">Contact Us</a>
      </div>

      <button class="mobile-menu-button" aria-label="Toggle menu">☰</button>

      <div class="header-right-section">
        <a class="cart-link" href="../pages/checkOut.html">
          <img
            class="cart-icon"
            src="../src/icons/header-icons/cart-icon.png"
          />
          <div class="cart-quantity">0</div>
          <div class="cart-text">Cart</div>
        </a>
      </div>
    </div>

    <div class="heading-section">
      <h2 class="heading">Adopt, Love, Repeat !</h2>
      <p class="heading-description">
        Click on a kitty to meet your future best friend!
      </p>
      <div class="admin-section">
        <?php if (isAdmin()): ?>
          <a href="add-cat.php" class="add-cat-button">Add New Cat</a>
          <br><br>
          <a href="admin-login.php?logout=1" class="admin-login-link">Logout</a>
        <?php else: ?>
          <a href="admin-login.php" class="admin-login-link">Admin Login</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="cats-grid">
      <?php foreach ($cats as $cat): ?>
      <div class="cats-container">
        <img src="../<?php echo htmlspecialchars($cat['image_path']); ?>" 
             alt="<?php echo htmlspecialchars($cat['name']); ?>" />
        <div class="overlay">
          <a class="open" href="cat-details.php?id=<?php echo $cat['id']; ?>">Open</a>
          <?php if (isAdmin()): ?>
          <div class="admin-actions">
            <a href="add-cat.php?id=<?php echo $cat['id']; ?>" class="edit-button">Edit</a>
            <a href="delete-cat.php?id=<?php echo $cat['id']; ?>" 
               class="delete-button" 
               onclick="return confirm('Are you sure you want to delete this cat?')">Delete</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <script src="../js/main.js"></script>
  </body>
</html> 