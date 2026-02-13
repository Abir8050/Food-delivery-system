<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $input = $_POST;

    // Basic Validation
    if (empty($input['name'])) {
        $errors['name'] = 'Name is required';
    }
    if (empty($input['description'])) {
        $errors['description'] = 'Description is required';
    }
    if (!isset($input['price']) || !is_numeric($input['price']) || $input['price'] < 0) {
        $errors['price'] = 'Price is required and must be a positive number';
    }
    if (empty($input['id'])) {
        // Should not happen normally
        header('Location: view_menu.php');
        exit;
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header("Location: edit_menu.php?id=" . $input['id']);
        exit;
    }

    $id = mysqli_real_escape_string($conn, $input['id']);
    $name = mysqli_real_escape_string($conn, $input['name']);
    $description = mysqli_real_escape_string($conn, $input['description']);
    $price = $input['price'];
    $restaurantOwnerId = $_SESSION['user_id'];

    // Verify ownership before update
    $check_query = "SELECT id FROM menus WHERE id = '$id' AND restaurant_owner_id = '$restaurantOwnerId'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) === 0) {
        die("Unauthorized access or item not found.");
    }

    // Handle File Upload
    $imageUpdateSql = "";
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
                $imagePath = 'assets/uploads/menu/' . $fileName; // Store relative path
                $imageUpdateSql = ", image_path = '$imagePath'";
            } else {
                $_SESSION['errors'] = ['Failed to upload image.'];
                header("Location: edit_menu.php?id=" . $input['id']);
                exit;
            }
        } else {
            $_SESSION['errors'] = ['Invalid file type.'];
            header("Location: edit_menu.php?id=" . $input['id']);
            exit;
        }
    }

    $query = "UPDATE menus SET name = '$name', description = '$description', price = '$price' $imageUpdateSql WHERE id = '$id' AND restaurant_owner_id = '$restaurantOwnerId'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = 'Menu item updated successfully';
    } else {
        $_SESSION['errors'] = ['Failed to update menu item: ' . mysqli_error($conn)];
        header("Location: edit_menu.php?id=" . $input['id']);
        exit;
    }

    header('Location: view_menu.php');
    exit;
} else {
    header('Location: view_menu.php');
    exit;
}
?>