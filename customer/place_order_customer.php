<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../auth/login.php');
    exit;
}

include '../includes/db_connect.php';
$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;

if ($menu_id === 0) {
    echo "Invalid Menu Item";
    exit;
}

// Fetch menu details
// Fetch menu details with Discount
$query = "SELECT m.*, r.restaurant_name, 
          (SELECT MAX(discount_percent) FROM promotional_offers po 
           WHERE po.restaurant_owner_id = m.restaurant_owner_id 
           AND po.active = 1 
           AND CURDATE() BETWEEN po.start_date AND po.end_date) as discount_percent
          FROM menus m 
          JOIN restaurant_owners r ON m.restaurant_owner_id = r.id 
          WHERE m.id = '$menu_id'";
$result = mysqli_query($conn, $query);
$menuItem = mysqli_fetch_assoc($result);

if (!$menuItem) {
    echo "Menu item not found.";
    exit;
}

// Calculate Price
$originalPrice = $menuItem['price'];
$discount = $menuItem['discount_percent'];
$hasDiscount = !empty($discount) && $discount > 0;
$unitPrice = $hasDiscount ? $originalPrice - ($originalPrice * $discount / 100) : $originalPrice;


// Fetch Customer Info to pre-fill
$customerId = $_SESSION['user_id'];
$queryCust = "SELECT * FROM customers WHERE id = '$customerId'";
$resultCust = mysqli_query($conn, $queryCust);
$customer = mysqli_fetch_assoc($resultCust);

// Handle Order Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity']);
    if ($quantity < 1)
        $quantity = 1;

    $totalAmount = $unitPrice * $quantity;
    $restaurantId = $menuItem['restaurant_owner_id'];
    $customerName = mysqli_real_escape_string($conn, $customer['username']); // Or use name if available
    $customerContact = mysqli_real_escape_string($conn, $_POST['contact']);
    $customerAddress = mysqli_real_escape_string($conn, $_POST['address']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    // Create Order
    $queryOrder = "INSERT INTO orders (restaurant_owner_id, customer_name, customer_contact, customer_address, total_amount, status, payment) 
                   VALUES ('$restaurantId', '$customerName', '$customerContact', '$customerAddress', '$totalAmount', 'Pending', '$payment')";

    if (mysqli_query($conn, $queryOrder)) {
        $orderId = mysqli_insert_id($conn);

        // Create Order Item
        // Store the actual Unit Price at time of purchase
        $queryItem = "INSERT INTO order_items (order_id, menu_id, quantity, price) 
                      VALUES ('$orderId', '$menu_id', '$quantity', '$unitPrice')";
        mysqli_query($conn, $queryItem);

        $_SESSION['message'] = "Order placed successfully!";
        header('Location: my_orders.php'); // Redirect to my orders logic
        exit;
    } else {
        $error = "Failed to place order: " . mysqli_error($conn);
    }
}

$page_title = "Confirm Order";
include '../includes/header.php';
?>

<div class="container" style="max-width: 800px;">
    <div class="card">
        <h2 style="border-bottom: 2px solid var(--primary-color); padding-bottom: 1rem; margin-bottom: 2rem;">Confirm
            Your Order</h2>

        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 1rem;">Item Details</h3>
                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px;">
                    <h4 style="margin: 0 0 0.5rem 0;">
                        <?php echo htmlspecialchars($menuItem['name']); ?>
                    </h4>
                    <p class="text-muted" style="margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($menuItem['description']); ?>
                    </p>
                    <p><strong>Restaurant:</strong>
                        <?php echo htmlspecialchars($menuItem['restaurant_name']); ?>
                    </p>

                    <?php if ($hasDiscount): ?>
                        <div style="margin-top: 1rem;">
                            <span
                                style="display: block; font-size: 0.9rem; color: var(--text-muted); text-decoration: line-through;">
                                $<?php echo number_format($originalPrice, 2); ?>
                            </span>
                            <span style="font-size: 1.25rem; font-weight: bold; color: var(--primary-color);">
                                Price: $<?php echo number_format($unitPrice, 2); ?>
                            </span>
                            <span
                                style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; margin-left: 0.5rem;">
                                <?= number_format($discount) ?>% OFF
                            </span>
                        </div>
                    <?php else: ?>
                        <p style="font-size: 1.25rem; font-weight: bold; color: var(--primary-color); margin-top: 1rem;">
                            Price: $<?php echo number_format($unitPrice, 2); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <form method="post">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" onchange="updateTotal()">
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" name="contact" value="<?php echo htmlspecialchars($customer['contact']); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="address">Delivery Address</label>
                        <textarea name="address" rows="3"
                            required><?php echo htmlspecialchars($customer['address']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="payment">Payment Method</label>
                        <select name="payment" required>
                            <option value="Cash On Delivery">Cash On Delivery</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>

                    <div
                        style="margin-top: 2rem; padding: 1rem; background: #e8f5e9; border-radius: 8px; text-align: center;">
                        <span style="display: block; font-size: 0.9rem; color: var(--text-muted);">Total Amount</span>
                        <strong style="font-size: 1.5rem; color: var(--primary-dark);" id="totalDisplay">
                            $
                            <?php echo number_format($unitPrice, 2); ?>
                        </strong>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">Confirm
                        Order</button>
                    <a href="customer_home.php" class="btn btn-block"
                        style="background: #eee; margin-top: 0.5rem;">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTotal() {
        const price = <?php echo $unitPrice; ?>;
        const qty = document.getElementById('quantity').value;
        const total = price * qty;
        document.getElementById('totalDisplay').innerText = '$' + total.toFixed(2);
    }
</script>

<?php include '../includes/footer.php'; ?>