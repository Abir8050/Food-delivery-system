<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $errors = [];



    if (empty($_POST['address'])) {
        $errors['address'] = "Please fill up your Address";
    } else {
        $input['address'] = $_POST['address'];
    }
    if (empty($_POST['contact'])) {
        $errors['contact'] = "Please fill up your Contact Number";
    } else {
        $input['contact'] = $_POST['contact'];
    }
    if (empty($_POST['email'])) {
        $errors['email'] = "Please fill up your Email";
    } else {
        $input['email'] = $_POST['email'];
    }
    if (empty($_POST['password'])) {
        $errors['password'] = "Please fill up your Password";
    } else {
        $input['password'] = $_POST['password'];
    }
    $password = $input['password'];
    $query = "SELECT * FROM restaurant_owners WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (!password_verify($password, $row['password'])) {
            //$_SESSION['user_id'] = $row['id'];
            $errors['login'] = 'Incorrect  password';
        }
    }
    // If the script reaches this point, the login details were incorrect


    if (empty($errors)) {
        // Update the user's profile in the database

        $address = mysqli_real_escape_string($conn, $input['address']);
        $contact = mysqli_real_escape_string($conn, $input['contact']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        //$password = $input['password'];
        // Repeat for other fields as needed

        $query = "UPDATE restaurant_owners SET address = '$address' WHERE id = '$user_id'";
        $query = "UPDATE restaurant_owners SET email = '$email' WHERE id = '$user_id'";
        $query = "UPDATE restaurant_owners SET contact = '$contact' WHERE id = '$user_id'";
        // Repeat for other fields as needed

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = 'Profile updated successfully';
            header('Location: profile.php');
            exit;
        } else {
            $errors[] = 'Failed to update profile: ' . mysqli_error($conn);
        }
    }

    // If there are errors, store them in the session and redirect back to the form
    $_SESSION['errors'] = $errors;
    header('Location: update_profile.php');
    exit;
}
?>