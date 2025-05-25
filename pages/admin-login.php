<?php
require_once __DIR__ . '/../includes/auth.php';

// Handle logout
if (isset($_GET['logout'])) {
    logout();
    header('Location: cats-lists.php');
    exit;
}

// If already logged in, redirect to cats list
if (isAdmin()) {
    header('Location: cats-lists.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: cats-lists.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MeowTopia</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Style/header-css/header.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto 40px;
            padding: 20px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'IBM Plex Sans', sans-serif;
        }
        .submit-button {
            background-color: #d23449;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            width: 100%;
        }
        .submit-button:hover {
            background-color: #b82a3d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(210, 52, 73, 0.2);
        }
        .error {
            color: #d23449;
            margin-bottom: 20px;
            padding: 10px;
            background: #fff5f5;
            border-radius: 5px;
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
    </div>

    <div class="login-container">
        <h1>Admin Login</h1>
        
        <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="submit-button">Login</button>
        </form>
    </div>
</body>
</html> 