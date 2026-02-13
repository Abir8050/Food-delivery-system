<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../includes/db_connect.php';

    $errors = [];
    $input = [];

    // Validation
    if (empty($_POST['username'])) {
        $errors['username'] = "Please fill up your Username";
    } else {
        $input['username'] = $_POST['username'];
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "Please fill up your Password";
    } else {
        $input['password'] = $_POST['password'];
    }

    if (empty($_POST['confirmPassword'])) {
        $errors['confirmPassword'] = "Please confirm your Password";
    } else if ($_POST['password'] !== $_POST['confirmPassword']) {
        $errors['confirmPassword'] = "Passwords do not match";
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "Please fill up your Email";
    } else {
        $input['email'] = $_POST['email'];
    }

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

    if (empty($_POST['securityQuestion'])) {
        $errors['securityQuestion'] = "Please select a security question";
    } else {
        $input['securityQuestion'] = $_POST['securityQuestion'];
    }

    if (empty($_POST['securityAnswer'])) {
        $errors['securityAnswer'] = "Please give an answer";
    } else {
        $input['securityAnswer'] = $_POST['securityAnswer'];
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header("Location: signup_customer.php");
        exit;
    } else {
        // Sanitize data
        $username = mysqli_real_escape_string($conn, $input['username']);
        $password = mysqli_real_escape_string($conn, password_hash($input['password'], PASSWORD_DEFAULT));
        $email = mysqli_real_escape_string($conn, $input['email']);
        $address = mysqli_real_escape_string($conn, $input['address']);
        $contact = mysqli_real_escape_string($conn, $input['contact']);
        $securityQuestion = mysqli_real_escape_string($conn, $input['securityQuestion']);
        $securityAnswer = mysqli_real_escape_string($conn, password_hash($input['securityAnswer'], PASSWORD_DEFAULT));

        // Insert data into database
        $query = "INSERT INTO customers (username, password, email, address, contact, securityQuestion, securityAnswer)
         VALUES ('$username', '$password', '$email', '$address', '$contact', '$securityQuestion', '$securityAnswer')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Account created successfully! Please login.";
            header("Location: login.php");
            exit;
        } else {
            // Check for duplicate entry
            if (mysqli_errno($conn) == 1062) {
                $errors['username'] = "Username already exists";
                $_SESSION['errors'] = $errors;
                $_SESSION['input'] = $input;
                header("Location: signup_customer.php");
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
}
?>