<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer') {
    $order_id = intval($_POST['order_id']);
    $restaurant_owner_id = intval($_POST['restaurant_owner_id']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $customer_name = $_SESSION['username'];
    $safe_name = mysqli_real_escape_string($conn, $customer_name);

    // Validate inputs
    if ($rating < 1 || $rating > 10) {
        die("Invalid rating.");
    }

    // Verify order again (security)
    $sql = "SELECT id FROM orders WHERE id = '$order_id' AND customer_name = '$safe_name' AND status = 'Completed'";
    $result = mysqli_query($conn, $sql);

    // Check if already reviewed (auto-fix schema if needed)
    // First, ensure reviews table has order_id column
    $colCheck = mysqli_query($conn, "SHOW COLUMNS FROM reviews LIKE 'order_id'");
    if (mysqli_num_rows($colCheck) == 0) {
        mysqli_query($conn, "ALTER TABLE reviews ADD COLUMN order_id INT, ADD FOREIGN KEY (order_id) REFERENCES orders(id)");
    }

    if (mysqli_num_rows($result) > 0) {
        $checkReview = mysqli_query($conn, "SELECT id FROM reviews WHERE order_id = '$order_id'");
        if (mysqli_num_rows($checkReview) == 0) {
            $insert = "INSERT INTO reviews (restaurant_owner_id, order_id, customer_name, rating, comment) VALUES ('$restaurant_owner_id', '$order_id', '$safe_name', '$rating', '$comment')";
            if (mysqli_query($conn, $insert)) {
                // Success
                header("Location: my_orders.php?msg=Review submitted successfully");
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Order already reviewed.";
        }
    } else {
        echo "Invalid order.";
    }
} else {
    header('Location: ../auth/login.php');
}
?>