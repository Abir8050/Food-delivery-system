<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../includes/db_connect.php';

    $errors = [];


    $input = [];

    // Validation for username
    if (empty($_POST['username'])) {
        $errors['username'] = "Please fill up your Username";
    } else {
        $input['username'] = $_POST['username'];
    }

    // Validation for password
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

    // Validation for email
    if (empty($_POST['email'])) {
        $errors['email'] = "Please fill up your Email";
    } else {
        $input['email'] = $_POST['email'];
    }

    // Validation for restaurant name
    if (empty($_POST['restaurantName'])) {
        $errors['restaurantName'] = "Please fill up your Restaurant Name";
    } else {
        $input['restaurantName'] = $_POST['restaurantName'];
    }

    // Validation for address
    if (empty($_POST['address'])) {
        $errors['address'] = "Please fill up your Address";
    } else {
        $input['address'] = $_POST['address'];
    }

    // Validation for contact
    if (empty($_POST['contact'])) {
        $errors['contact'] = "Please fill up your Contact Number";
    } else {
        $input['contact'] = $_POST['contact'];
    }
    if (empty($_POST['securityQuestion'])) {
        $errors['securityQuestion'] = "Please select a sequrity question";
    } else {
        $input['securityQuestion'] = $_POST['ecurituQuestion'];
    }
    if (empty($_POST['securityAnswer'])) {
        $errors['securityAnswer'] = "Please give an answer according to your  question";
    } else {
        $input['securityAnswer'] = $_POST['ecurituAnswer'];
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header("Location: signup.php");
        exit;
    } else {
        // Sanitize data
        $username = mysqli_real_escape_string($conn, $input['username']);
        $password = mysqli_real_escape_string($conn, password_hash($input['password'], PASSWORD_DEFAULT));
        $email = mysqli_real_escape_string($conn, $input['email']);
        $restaurantName = mysqli_real_escape_string($conn, $input['restaurantName']);
        $address = mysqli_real_escape_string($conn, $input['address']);
        $contact = mysqli_real_escape_string($conn, $input['contact']);
        $securityQuestion = $_POST['securityQuestion'];
        $securityAnswer = password_hash($_POST['securityAnswer'], PASSWORD_DEFAULT);

        // Insert data into database
        $query = "INSERT INTO restaurant_owners (username, password, email, restaurant_name, address, contact, securityQuestion, securityAnswer)
         VALUES ('$username', '$password', '$email', '$restaurantName', '$address', '$contact', '$securityQuestion', '$securityAnswer')";
        //$query = "INSERT INTO restaurant_owners (username, password, email, restaurant_name, address, contact,securityQuestion, securityAnswer) 
        //VALUES ('{$_SESSION['username']}', '{$_SESSION['password']}', '{$_SESSION['email']}', '{$_SESSION['restaurantName']}', '{$_SESSION['address']}', '{$_SESSION['contact']}','{$_SESSION['securityQuestion']}'{$_SESSION['securityAnswer']})";
        if (mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
?>