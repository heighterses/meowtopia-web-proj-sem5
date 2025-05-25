<?php
require_once __DIR__ . '/../includes/cat_operations.php';

if (!isset($_GET['id'])) {
    header('Location: cats-lists.php');
    exit;
}

try {
    deleteCat($_GET['id']);
    header('Location: cats-lists.php');
} catch (Exception $e) {
    // You might want to handle the error differently
    die("Error deleting cat: " . $e->getMessage());
}
?> 