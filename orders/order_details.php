<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Order Details #" . ($_GET['id'] ?? '');
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$orderId = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch order details
$orderQuery = "SELECT * FROM orders WHERE id = '$orderId' AND restaurant_owner_id = '$restaurantOwnerId'";
$orderResult = mysqli_query($conn, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);

if (!$order) {
    echo "<div class='alert alert-danger'>Order not found or you don't have permission to view it.</div>";
    include 'footer.php';
    exit;
}

// Fetch order items
$itemsQuery = "SELECT oi.*, m.name as menu_name 
               FROM order_items oi 
               JOIN menus m ON m.id = oi.menu_id 
               WHERE order_id = '$orderId'";
$itemsResult = mysqli_query($conn, $itemsQuery);
?>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Customer & Order Info -->
    <div>
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--primary-color); display: inline-block;">
                Order Info</h3>

            <div style="margin-bottom: 1rem;">
                <label class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Customer Name</label>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($order['customer_name']) ?></div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Contact info</label>
                <div><i class="fas fa-phone"></i> <?= htmlspecialchars($order['customer_contact']) ?></div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Delivery
                    Address</label>
                <div style="font-size: 0.9rem; border-left: 2px solid var(--border-color); padding-left: 0.5rem;">
                    <?= nl2br(htmlspecialchars($order['customer_address'])) ?>
                </div>
            </div>

            <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--border-color);">

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="text-muted">Status:</span>
                <?php
                $status_class = '';
                switch ($order['status']) {
                    case 'Pending':
                        $status_class = 'background: #fef3c7; color: #92400e;';
                        break;
                    case 'Processing':
                        $status_class = 'background: #dbeafe; color: #1e40af;';
                        break;
                    case 'Completed':
                        $status_class = 'background: #dcfce7; color: #166534;';
                        break;
                    case 'Cancelled':
                        $status_class = 'background: #fee2e2; color: #991b1b;';
                        break;
                }
                ?>
                <span
                    style="padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; <?= $status_class ?>">
                    <?= htmlspecialchars($order['status']) ?>
                </span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                <span class="text-muted">Ordered On:</span>
                <span style="font-size: 0.85rem;"><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></span>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.5rem;">
            <?php if ($order['status'] == 'Pending'): ?>
                <a href="process_order.php?id=<?= $order['id'] ?>&action=process" class="btn btn-primary btn-block">Start
                    Processing</a>
            <?php endif; ?>

            <?php if ($order['status'] == 'Processing'): ?>
                <a href="process_order.php?id=<?= $order['id'] ?>&action=complete" class="btn btn-primary btn-block"
                    style="background-color: var(--success-color);">Complete Order</a>
            <?php endif; ?>

            <a href="order_dashboard.php" class="btn btn-block"
                style="background: #ecf0f1; border: 1px solid #bdc3c7;">Back to List</a>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card">
        <h3 style="margin-bottom: 2rem;"><i class="fas fa-receipt"></i> Order Summary</h3>

        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600;"><?= htmlspecialchars($item['menu_name']) ?></div>
                        </td>
                        <td style="text-align: center;">x<?= htmlspecialchars($item['quantity']) ?></td>
                        <td style="text-align: right;">$<?= number_format($item['price'], 2) ?></td>
                        <td style="text-align: right; font-weight: 600;">
                            $<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; padding-top: 2rem; font-size: 1.1rem;">Grand Total:</td>
                    <td
                        style="text-align: right; padding-top: 2rem; font-size: 1.5rem; font-weight: 800; color: var(--primary-dark);">
                        $<?= number_format($order['total_amount'], 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
include '../includes/footer.php';
?>