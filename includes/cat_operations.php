<?php
require_once __DIR__ . '/../config/database.php';

// Get all cats
function getAllCats() {
    $conn = getDBConnection();
    $stmt = $conn->query("SELECT * FROM cats ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a single cat by ID
function getCatById($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM cats WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Create a new cat
function createCat($data) {
    $conn = getDBConnection();
    $sql = "INSERT INTO cats (name, age, breed, gender, color, image_path, story, 
            personality_traits, health_care, adoption_requirements) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['age'],
        $data['breed'],
        $data['gender'],
        $data['color'],
        $data['image_path'],
        $data['story'],
        $data['personality_traits'],
        $data['health_care'],
        $data['adoption_requirements']
    ]);
}

// Update a cat
function updateCat($id, $data) {
    $conn = getDBConnection();
    $sql = "UPDATE cats SET 
            name = ?, 
            age = ?, 
            breed = ?, 
            gender = ?, 
            color = ?, 
            image_path = ?, 
            story = ?, 
            personality_traits = ?, 
            health_care = ?, 
            adoption_requirements = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['age'],
        $data['breed'],
        $data['gender'],
        $data['color'],
        $data['image_path'],
        $data['story'],
        $data['personality_traits'],
        $data['health_care'],
        $data['adoption_requirements'],
        $id
    ]);
}

// Delete a cat
function deleteCat($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM cats WHERE id = ?");
    return $stmt->execute([$id]);
}

// Handle file upload
function handleImageUpload($file) {
    $target_dir = "../src/Images/adoption-page-imgs/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is a actual image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        throw new Exception("File is not an image.");
    }
    
    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        throw new Exception("File is too large.");
    }
    
    // Allow certain file formats
    if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
        throw new Exception("Only JPG, JPEG & PNG files are allowed.");
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "src/Images/adoption-page-imgs/" . $new_filename;
    } else {
        throw new Exception("Failed to upload file.");
    }
}
?> 