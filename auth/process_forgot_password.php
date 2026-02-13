<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $input = [];

    // Validation for username
    if (empty($_POST['username'])) {
        $errors['username'] = "Please fill in your Username";
    } else {
        $input['username'] = $_POST['username'];
    }

    // Validation for security answer
    if (empty($_POST['securityAnswer'])) {
        $errors['securityAnswer'] = "Please provide an answer to the security question";
    } else {
        $input['securityAnswer'] = $_POST['securityAnswer'];
    }

    // Validation for new password
    if (empty($_POST['newPassword'])) {
        $errors['newPassword'] = "Please enter your new password";
    } else {
        $input['newPassword'] = $_POST['newPassword'];
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header('Location: forgot_password.php');
        exit;
    } else {
        $username = mysqli_real_escape_string($conn, $input['username']);
        $query = "SELECT * FROM restaurant_owners WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($input['securityAnswer'], $user['securityAnswer'])) {
                $newPasswordHashed = password_hash($input['newPassword'], PASSWORD_DEFAULT);
                $updateQuery = "UPDATE restaurant_owners SET password = '$newPasswordHashed' WHERE username = '$username'";
                
                if (mysqli_query($conn, $updateQuery)) {
                    echo "Password successfully updated. You can now <a href='login.php'>log in</a> with your new password.";
                } else {
                    echo "Error updating password: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['errors']['securityAnswer'] = "Incorrect answer to security question";
                header('Location: forgot_password.php');
                exit;
            }
        } else {
            $_SESSION['errors']['username'] = "Username not found";
            header('Location: forgot_password.php');
            exit;
        }
    }
}
?>
