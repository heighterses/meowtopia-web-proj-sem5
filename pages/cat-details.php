<?php
require_once __DIR__ . '/../includes/cat_operations.php';

if (!isset($_GET['id'])) {
    header('Location: cats-lists.php');
    exit;
}

$cat = getCatById($_GET['id']);
if (!$cat) {
    header('Location: cats-lists.php');
    exit;
}

// Convert stored text fields from JSON to arrays
$personality_traits = json_decode($cat['personality_traits'], true);
$health_care = json_decode($cat['health_care'], true);
$adoption_requirements = json_decode($cat['adoption_requirements'], true);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($cat['name']); ?> - MeowTopia</title>
    <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../Style/header-css/header.css" />
    <link rel="stylesheet" href="../Style/cat-details-css/cat-details.css" />
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

    <div class="cat-details-container">
      <div class="cat-profile">
        <div class="cat-image-container">
          <img src="../<?php echo htmlspecialchars($cat['image_path']); ?>" 
               alt="<?php echo htmlspecialchars($cat['name']); ?>" 
               class="cat-image" />
        </div>
        <div class="cat-info">
          <h1 class="cat-name"><?php echo htmlspecialchars($cat['name']); ?></h1>
          <div class="cat-stats">
            <div class="stat">
              <span class="stat-label">Age:</span>
              <span class="stat-value"><?php echo htmlspecialchars($cat['age']); ?></span>
            </div>
            <div class="stat">
              <span class="stat-label">Breed:</span>
              <span class="stat-value"><?php echo htmlspecialchars($cat['breed']); ?></span>
            </div>
            <div class="stat">
              <span class="stat-label">Gender:</span>
              <span class="stat-value"><?php echo htmlspecialchars($cat['gender']); ?></span>
            </div>
            <div class="stat">
              <span class="stat-label">Color:</span>
              <span class="stat-value"><?php echo htmlspecialchars($cat['color']); ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="cat-story">
        <h2><?php echo htmlspecialchars($cat['name']); ?>'s Story</h2>
        <p><?php echo nl2br(htmlspecialchars($cat['story'])); ?></p>
        
        <h3>Personality Traits</h3>
        <ul>
          <?php foreach ($personality_traits as $trait): ?>
          <li><?php echo htmlspecialchars($trait); ?></li>
          <?php endforeach; ?>
        </ul>
        
        <h3>Health & Care</h3>
        <ul>
          <?php foreach ($health_care as $care): ?>
          <li><?php echo htmlspecialchars($care); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="adoption-info">
        <h2>Adoption Information</h2>
        <div class="adoption-requirements">
          <h3>Requirements</h3>
          <ul>
            <?php foreach ($adoption_requirements as $requirement): ?>
            <li><?php echo htmlspecialchars($requirement); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <button class="adopt-button">Adopt <?php echo htmlspecialchars($cat['name']); ?></button>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <div class="admin-actions">
          <a href="edit-cat.php?id=<?php echo $cat['id']; ?>" class="edit-button">Edit Cat</a>
          <a href="delete-cat.php?id=<?php echo $cat['id']; ?>" 
             class="delete-button" 
             onclick="return confirm('Are you sure you want to delete this cat?')">Delete Cat</a>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <script src="../js/main.js"></script>
  </body>
</html> 