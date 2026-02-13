<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../auth/login.php');
    exit;
}
$page_title = "My Orders";
// header.php includes db_connect.php
include '../includes/header.php';

$username = $_SESSION['username'];
// Fetch orders for this customer
// Note: orders table stores customer_name, which matches the username in our current logic
$safe_username = mysqli_real_escape_string($conn, $username);

// We need to join with restaurant_owners to get the restaurant name
$query = "SELECT o.*, r.restaurant_name FROM orders o 
          JOIN restaurant_owners r ON o.restaurant_owner_id = r.id 
          WHERE o.customer_name = '$safe_username' 
          ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    // Graceful fallback or error show
    echo "<div class='container'><p class='error-msg'>Error fetching orders: " . mysqli_error($conn) . "</p></div>";
}
?>

<div class="container">
    <div class="card">
        <h2 style="margin-bottom: 2rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem;">
            <i class="fas fa-receipt"></i> My Order History
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <th style="text-align: left; padding: 1rem;">Order #</th>
                        <th style="text-align: left; padding: 1rem;">Restaurant</th>
                        <th style="text-align: left; padding: 1rem;">Date</th>
                        <th style="text-align: left; padding: 1rem;">Total</th>
                        <th style="text-align: center; padding: 1rem;">Status</th>
                        <th style="text-align: right; padding: 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)):
                            $status_color = '#6c757d'; // Default gray
                            switch ($row['status']) {
                                case 'Pending':
                                    $status_color = '#d35400';
                                    break; // Orange
                                case 'Processing':
                                    $status_color = '#2980b9';
                                    break; // Blue
                                case 'Completed':
                                    $status_color = '#27ae60';
                                    break; // Green
                                case 'Cancelled':
                                    $status_color = '#c0392b';
                                    break; // Red
                            }
                            ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1rem; font-weight: bold;">#
                                    <?php echo $row['id']; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo htmlspecialchars($row['restaurant_name']); ?>
                                </td>
                                <td style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                                    <?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?>
                                </td>
                                <td style="padding: 1rem; font-weight: bold;">$
                                    <?php echo number_format($row['total_amount'], 2); ?>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span
                                        style="background: <?php echo $status_color; ?>20; color: <?php echo $status_color; ?>; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <?php if ($row['status'] === 'Completed'):
                                        // Check if reviewed
                                        $orderId = $row['id'];
                                        // Ensure order_id column exists before querying (graceful degradation if DB not updated yet manually)
                                        // Ideally we assume DB is fixed, but let's be safe or just run query.
                                        // NOTE: Since I am editing a loop, I shouldn't run SHOW COLUMNS here every time. 
                                        // Assuming db_fix.php or sql was run.
                                        $revCheck = mysqli_query($conn, "SELECT id FROM reviews WHERE order_id = '$orderId'");
                                        $isReviewed = ($revCheck && mysqli_num_rows($revCheck) > 0);
                                        ?>
                                        <?php if ($isReviewed): ?>
                                            <button class="btn btn-sm" disabled
                                                style="background: #ccc; cursor: not-allowed; font-size: 0.8rem;">Reviewed</button>
                                        <?php else: ?>
                                            <a href="give_review.php?order_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm"
                                                style="font-size: 0.8rem;">Rate & Review</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem;">
                                <i class="fas fa-utensils fa-3x" style="color: #eee; margin-bottom: 1rem;"></i>
                                <p class="text-muted">You haven't placed any orders yet.</p>
                                <a href="customer_home.php" class="btn btn-primary" style="margin-top: 1rem;">Order Food</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>