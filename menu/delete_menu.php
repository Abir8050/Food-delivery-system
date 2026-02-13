<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'], $_GET['id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$menuId = mysqli_real_escape_string($conn, $_GET['id']);
$query = "DELETE FROM menus WHERE id = '$menuId' AND restaurant_owner_id = '$restaurantOwnerId'";
if (mysqli_query($conn, $query)) {
    $_SESSION['message'] = 'Menu item deleted successfully';
} else {
    $_SESSION['errors'] = ['Failed to delete menu item'];
}
header('Location: view_menu.php');
exit;
?>