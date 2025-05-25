<?php
require_once __DIR__ . '/../includes/cat_operations.php';

$cat = null;
$is_edit = false;

if (isset($_GET['id'])) {
    $cat = getCatById($_GET['id']);
    $is_edit = true;
    if (!$cat) {
        header('Location: cats-lists.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'name' => $_POST['name'],
            'age' => $_POST['age'],
            'breed' => $_POST['breed'],
            'gender' => $_POST['gender'],
            'color' => $_POST['color'],
            'story' => $_POST['story'],
            'personality_traits' => json_encode(array_filter(explode("\n", $_POST['personality_traits']))),
            'health_care' => json_encode(array_filter(explode("\n", $_POST['health_care']))),
            'adoption_requirements' => json_encode(array_filter(explode("\n", $_POST['adoption_requirements'])))
        ];

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image_path'] = handleImageUpload($_FILES['image']);
        } elseif ($is_edit) {
            $data['image_path'] = $cat['image_path'];
        } else {
            throw new Exception("Image is required");
        }

        if ($is_edit) {
            updateCat($_GET['id'], $data);
        } else {
            createCat($data);
        }

        header('Location: cats-lists.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? 'Edit' : 'Add'; ?> Cat - MeowTopia</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Style/header-css/header.css">
    <style>
        .form-container {
            max-width: 800px;
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
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'IBM Plex Sans', sans-serif;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
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
        <!-- Header content same as other pages -->
    </div>

    <div class="form-container">
        <h1><?php echo $is_edit ? 'Edit' : 'Add'; ?> Cat</h1>
        
        <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Cat Name:</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo $cat ? htmlspecialchars($cat['name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="text" id="age" name="age" required 
                       value="<?php echo $cat ? htmlspecialchars($cat['age']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="breed">Breed:</label>
                <input type="text" id="breed" name="breed" required 
                       value="<?php echo $cat ? htmlspecialchars($cat['breed']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo $cat && $cat['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $cat && $cat['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" required 
                       value="<?php echo $cat ? htmlspecialchars($cat['color']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="image">Cat Image:</label>
                <input type="file" id="image" name="image" accept="image/*" <?php echo $is_edit ? '' : 'required'; ?>>
                <?php if ($is_edit && $cat['image_path']): ?>
                <p>Current image: <?php echo htmlspecialchars($cat['image_path']); ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="story">Cat's Story:</label>
                <textarea id="story" name="story" required><?php echo $cat ? htmlspecialchars($cat['story']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="personality_traits">Personality Traits (one per line):</label>
                <textarea id="personality_traits" name="personality_traits" required><?php 
                    if ($cat) {
                        echo htmlspecialchars(implode("\n", json_decode($cat['personality_traits'], true)));
                    }
                ?></textarea>
            </div>

            <div class="form-group">
                <label for="health_care">Health & Care (one per line):</label>
                <textarea id="health_care" name="health_care" required><?php 
                    if ($cat) {
                        echo htmlspecialchars(implode("\n", json_decode($cat['health_care'], true)));
                    }
                ?></textarea>
            </div>

            <div class="form-group">
                <label for="adoption_requirements">Adoption Requirements (one per line):</label>
                <textarea id="adoption_requirements" name="adoption_requirements" required><?php 
                    if ($cat) {
                        echo htmlspecialchars(implode("\n", json_decode($cat['adoption_requirements'], true)));
                    }
                ?></textarea>
            </div>

            <button type="submit" class="submit-button">
                <?php echo $is_edit ? 'Update Cat' : 'Add Cat'; ?>
            </button>
        </form>
    </div>

    <script src="../js/main.js"></script>
</body>
</html> 