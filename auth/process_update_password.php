<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $errors = [];

    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Validate the form input
    if (empty($currentPassword) ) {
        $errors['currentPassword'] = 'Current password field is empty';
    }

    if (empty($newPassword) ) {
        $errors['newPassword'] = 'New Password field is empty';
    }
    if (empty($currentPassword) ) {
        $errors['confirmPassword'] = 'Confirm Password field is empty';
    }
    if ($newPassword !== $confirmNewPassword) {
        $errors['pass'] = 'New passwords do not match';
    }

    // Check if the current password is correct
    $query = "SELECT password FROM restaurant_owners WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (!password_verify($currentPassword, $user['password'])) {
        $errors['currentPass'] = 'Current password is incorrect';
    }

    if (empty($errors)) {
        // Update the user's password in the database
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE restaurant_owners SET password = '$hashedNewPassword' WHERE id = '$user_id'";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = 'Password updated successfully';
            header('Location: welcome.php');
            exit;
        } else {
            
            $errors[] = 'Failed to update password: ' . mysqli_error($conn);
        }
    }

    // If there are errors, store them in the session and redirect back to the form
    $_SESSION['errors'] = $errors;
    header('Location: update_password.php');
    exit;
}
?>
