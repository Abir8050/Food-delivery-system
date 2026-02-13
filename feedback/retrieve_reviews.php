<?php
/*
session_start();
include 'db_connect.php';

if(isset($_SESSION['user_id'])) {
    $restaurant_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit;

}



$reviews = [];

$query = "SELECT * FROM reviews WHERE restaurant_owner_id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $restaurant_id);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing query: " . mysqli_error($conn);
}

//mysqli_close($conn);*/
// retrieve_reviews.php

// Ensure this file establishes the database connection.
include '../includes/db_connect.php';

// Make sure the user is logged in, otherwise redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$restaurant_owner_id = $_SESSION['user_id'];

// Retrieve reviews and their feedback for the restaurant owner.
$reviewsQuery = "SELECT reviews.*, feedback.message AS feedback_message FROM reviews 
                 LEFT JOIN feedback ON reviews.id = feedback.review_id
                 WHERE reviews.restaurant_owner_id = ?";
$reviewsStmt = mysqli_prepare($conn, $reviewsQuery);
if ($reviewsStmt) {
    mysqli_stmt_bind_param($reviewsStmt, 'i', $restaurant_owner_id);
    mysqli_stmt_execute($reviewsStmt);
    $reviewsResult = mysqli_stmt_get_result($reviewsStmt);
    $reviews = mysqli_fetch_all($reviewsResult, MYSQLI_ASSOC);
    mysqli_stmt_close($reviewsStmt);
} else {
    $reviews = [];
}

mysqli_close($conn); // Close the connection since we are done with database operations.

?>