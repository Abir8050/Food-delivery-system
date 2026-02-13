<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Dashboard";
include '../includes/header.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'owner';

if ($role === 'customer') {
    header('Location: ../customer/customer_home.php');
    exit;
}

$query = "SELECT * FROM restaurant_owners WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

// Simple stats (mocked or fetched if queries were ready)
?>

<div class="welcome-section">
    <h1>Hello, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p class="text-muted">Welcome to your restaurant management dashboard.</p>
</div>

<div class="dashboard-grid" style="margin-top: 2rem;">
    <div class="card stat-card">
        <h3>Menu Items</h3>
        <div class="value">
            <?php
            $menu_q = "SELECT COUNT(*) as total FROM menus WHERE restaurant_owner_id = '$user_id'";
            $menu_r = mysqli_query($conn, $menu_q);
            $menu_data = mysqli_fetch_assoc($menu_r);
            echo $menu_data['total'];
            ?>
        </div>
        <a href="../menu/menu.php" class="btn btn-primary" style="margin-top: 1rem;">Manage Menu</a>
    </div>

    <div class="card stat-card">
        <h3>Active Orders</h3>
        <div class="value">
            <?php
            $order_q = "SELECT COUNT(*) as total FROM orders WHERE restaurant_owner_id = '$user_id' AND status != 'Completed' AND status != 'Cancelled'";
            $order_r = mysqli_query($conn, $order_q);
            $order_data = mysqli_fetch_assoc($order_r);
            echo $order_data['total'];
            ?>
        </div>
        <a href="../orders/order_dashboard.php" class="btn btn-primary" style="margin-top: 1rem;">View Orders</a>
    </div>

    <div class="card stat-card">
        <h3>Reviews</h3>
        <div class="value">
            <?php
            $rev_q = "SELECT COUNT(*) as total FROM reviews WHERE restaurant_owner_id = '$user_id'";
            $rev_r = mysqli_query($conn, $rev_q);
            $rev_data = mysqli_fetch_assoc($rev_r);
            echo $rev_data['total'];
            ?>
        </div>
        <a href="../feedback/review.php" class="btn btn-primary" style="margin-top: 1rem;">Manage Reviews</a>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <h2>Quick Actions</h2>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
        <a href="../profile/profile.php" class="btn" style="background: #ecf0f1; border: 1px solid #bdc3c7;">Update
            Profile</a>
        <a href="restaurant_codes.php" class="btn" style="background: #e67e22; color: white;">Launch Offer</a>
        <a href="../orders/payment.php" class="btn" style="background: #34495e; color: white;">Payment History</a>
        <a href="../auth/update_password.php" class="btn" style="background: #95a5a6; color: white;">Change Password</a>
    </div>
</div>

<?php
include '../includes/footer.php';
?>