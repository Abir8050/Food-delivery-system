<?php
/*session_start();
include 'db_connect.php';

$errors = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['review_id'])) {
        $errors['review_id'] = "Review ID is required";
    } else {
        $review_id = intval($_POST['review_id']);
    }

    if (empty($_POST['message'])) {
        $errors['message'] = "Feedback message is required";
    } else {
        $message = mysqli_real_escape_string($conn, $_POST['message']);
    }

    if (empty($errors)) {
        $restaurant_owner_id = $_SESSION['user_id'];
        $query = "INSERT INTO feedback (review_id, restaurant_owner_id, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iis', $review_id, $restaurant_owner_id, $message);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Feedback submitted successfully!";
        } else {
            $message = "Error submitting feedback: " . mysqli_error($conn);
        }
    }
    $_SESSION['errors'] = $errors;
    //$_SESSION['input'] = $input;
    header("Location: show_reviews.php");
    exit;
}*/

// Start the session if it has not been started
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}


include '../includes/db_connect.php'; // File to establish database connection
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];
    if (empty($_POST['message'])) {
        $errors['message'] = "Feedback message is required";
    } else {
        //$message = mysqli_real_escape_string($conn, $_POST['message']);
        $message = trim($_POST['message']);
    }

    //$message = trim($_POST['message']);
    $user_id = $_SESSION['user_id']; // Assuming the user_id session variable is the ID of the restaurant owner
    if (empty($errors)) {
        // Insert feedback into the database
        $insertQuery = "INSERT INTO feedback (review_id, restaurant_owner_id, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'iis', $review_id, $user_id, $message);
            $success = mysqli_stmt_execute($stmt);

            if (!$success) {
                // Handle error - can also log this to a file or database
                $_SESSION['error'] = 'Error submitting feedback. Please try again later.';
            }
            mysqli_stmt_close($stmt);
        } else {
            // Handle error - can also log this to a file or database
            $_SESSION['error'] = 'Error preparing to submit feedback. Please try again later.';
        }
    } else {
        // Handle invalid access
        $_SESSION['error'] = 'Invalid request method or missing data.';
    }
    $_SESSION['errors'] = $errors;
    //$_SESSION['input'] = $input;
    header("Location: review.php");
    exit;
}

mysqli_close($conn);

?>