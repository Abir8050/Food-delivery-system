<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$orderId = $_GET['id'];
$action = $_GET['action'];

$status = 'Pending';
if ($action == 'process') {
  $status = 'Processing';
} elseif ($action == 'cancel') {
  $status = 'Cancelled';
} elseif ($action == 'complete') {
  $status = 'Completed';
}

$query = "UPDATE orders SET status = '$status' WHERE id = '$orderId' AND restaurant_owner_id = '$restaurantOwnerId'";
if (mysqli_query($conn, $query)) {
  $_SESSION['message'] = 'Order status updated successfully';
} else {
  $_SESSION['errors'] = ['Failed to update order status'];
}
header('Location: order_dashboard.php');
exit;
?>