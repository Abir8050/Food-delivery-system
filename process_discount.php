<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $input = [];


    if (empty($_POST['name'])) {
        $errors['name'] = "Please give a Offer name";
    } else {
        $input['name'] = $_POST['name'];
    }

    if (empty($_POST['description'])) {
        $errors['description'] = "Please provide offer description";
    } else {
        $input['description'] = $_POST['description'];
    }

    if (empty($_POST['discount_percent'])) {
        $errors['discount_percent'] = "Please enter your discount Percent";
    } else {
        $input['discount_percent'] = $_POST['discount_percent'];
    }
    if (empty($_POST['start_date'])) {
        $errors['start_date'] = "Please enter your start Date";
    } else {
        $input['start_date'] = $_POST['start_date'];
    }
    if (empty($_POST['end_date'])) {
        $errors['end_date'] = "Please enter your end Date";
    } else {
        $input['end_date'] = $_POST['end_date'];
    }
    if (empty($_POST['active'])) {
        $errors['active'] = "Please give the tick";
    } else {
        $input['active'] = $_POST['active'];
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header('Location: dashboard/restaurant_codes.php');
        exit;
    } else {

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $discount_percent = mysqli_real_escape_string($conn, $_POST['discount_percent']);
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
        $active = isset($_POST['active']) ? 1 : 0;
        $restaurantOwnerId = $_SESSION['user_id'];
        $sql = "INSERT INTO promotional_offers (restaurant_owner_id,name, description, discount_percent, start_date, end_date, active) VALUES ('$restaurantOwnerId','$name', '$description', '$discount_percent', '$start_date', '$end_date', '$active')";

        if (mysqli_query($conn, $sql)) {
            header('Location: dashboard/restaurant_codes.php');

        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

    }
}
?>