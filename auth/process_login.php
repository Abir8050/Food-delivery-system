<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (count($errors) === 0) {
        $username = mysqli_real_escape_string($conn, $input['username']);
        $password = $input['password'];

        // Check Restaurant Owners
        $query = "SELECT * FROM restaurant_owners WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'owner';
                $_SESSION['username'] = $row['username'];
                header('Location: ../dashboard/welcome.php');
                exit;
            }
        }

        // Check Customers
        $query = "SELECT * FROM customers WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'customer';
                $_SESSION['username'] = $row['username'];
                header('Location: ../customer/customer_home.php');
                exit;
            }
        }

        // If the script reaches this point, the login details were incorrect
        $errors['login'] = 'Incorrect username or password';
    }

    // If there are any errors, redirect back to the login form
    $_SESSION['errors'] = $errors;
    $_SESSION['input'] = $input;
    header("Location: login.php");
    exit;
}
mysqli_close($conn);
?>