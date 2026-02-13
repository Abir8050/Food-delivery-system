<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . " - FoodHome" : "FoodHome - Restaurant Portal"; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar">
            <a href="../dashboard/welcome.php" class="nav-logo">
                <i class="fas fa-utensils"></i> FoodHome
            </a>
            <?php if (isset($_SESSION['user_id'])):
                $role = $_SESSION['role'] ?? 'owner';
                $username = $_SESSION['username'] ?? 'User';
                ?>
                <ul class="nav-links">
                    <?php if ($role === 'customer'): ?>
                        <li><a href="../customer/customer_home.php">Home</a></li>
                        <li><a href="../customer/my_orders.php">My Orders</a></li>
                        <li><a href="../profile/profile.php">Profile</a></li>
                    <?php else: ?>
                        <li><a href="../dashboard/welcome.php">Dashboard</a></li>
                        <li><a href="../menu/menu.php">Menu</a></li>
                        <li><a href="../orders/order_dashboard.php">Orders</a></li>
                        <li><a href="../profile/profile.php">Profile</a></li>
                    <?php endif; ?>
                    <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
                <div class="restaurant-badge">
                    <span class="badge-text">
                        <?php if ($role === 'owner'):
                            if (isset($_SESSION['restaurant_name'])) {
                                echo htmlspecialchars($_SESSION['restaurant_name']);
                            } else {
                                echo "Restaurant Owner";
                            }
                            ?>
                        <?php else: ?>
                            Hello, <?php echo htmlspecialchars($username); ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php else: ?>
                <ul class="nav-links">
                    <li><a href="../auth/login.php">Login</a></li>
                    <li><a href="../auth/register_selection.php" class="btn btn-sm btn-primary" style="color: white;">Join
                            Us</a></li>
                </ul>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">