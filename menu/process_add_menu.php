<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $input = $_POST;

    // Validation
    if (empty($input['name'])) {
        $errors['name'] = 'Name is required';
    }
    if (empty($input['description'])) {
        $errors['description'] = 'Description is required';
    }
    if (!isset($input['price']) || !is_numeric($input['price']) || $input['price'] < 0) {
        $errors['price'] = 'Price is required and must be a positive number';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header('Location: add_menu.php');
        exit;
    }

    $name = mysqli_real_escape_string($conn, $input['name']);
    $description = mysqli_real_escape_string($conn, $input['description']);
    $price = $input['price'];
    $restaurantOwnerId = $_SESSION['user_id'];
    $imagePath = '';

    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../assets/uploads/menu/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = 'assets/uploads/menu/' . $fileName; // Store relative path for DB
            } else {
                $_SESSION['errors'] = ['Failed to upload image.'];
                header('Location: add_menu.php');
                exit;
            }
        } else {
            $_SESSION['errors'] = ['Invalid file type. Only JPG, JPEG, PNG, GIF, & WEBP are allowed.'];
            header('Location: add_menu.php');
            exit;
        }
    }

    $query = "INSERT INTO menus (restaurant_owner_id, name, description, price, image_path) VALUES ('$restaurantOwnerId', '$name', '$description', '$price', '$imagePath')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = 'Menu item added successfully';
    } else {
        $_SESSION['errors'] = ['Failed to add menu item: ' . mysqli_error($conn)];
    }
    header('Location: view_menu.php');
    exit;
}
?>