<?php
session_start();

// Simple admin credentials - in a real application, these should be in a database
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // In production, use a secure password

function login($username, $password) {
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
        return true;
    }
    return false;
}

function logout() {
    unset($_SESSION['is_admin']);
    session_destroy();
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}
?> 