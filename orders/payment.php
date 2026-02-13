<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Payments & Withdrawals";
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$totalAmount = 0;
$withdrawnAmount = 0;

// 1. Fetch total earnings from completed orders
$sql = "SELECT SUM(total_amount) as total FROM orders WHERE restaurant_owner_id = '$restaurantOwnerId' AND status = 'Completed'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalAmount = $row['total'] ?? 0;
}

// 2. Fetch total withdrawn amount (Pending or Completed)
// Auto-fix: Check if table exists
$checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'withdrawals'");
if (mysqli_num_rows($checkTable) == 0) {
    $createTable = "CREATE TABLE withdrawals (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        restaurant_owner_id INT(11) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        status VARCHAR(20) DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (restaurant_owner_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    mysqli_query($conn, $createTable);
}

$sqlWithdraw = "SELECT SUM(amount) as total_withdrawn FROM withdrawals WHERE restaurant_owner_id = '$restaurantOwnerId' AND status IN ('Pending', 'Completed')";
$resultWithdraw = mysqli_query($conn, $sqlWithdraw);
if ($resultWithdraw) {
    $rowWithdraw = mysqli_fetch_assoc($resultWithdraw);
    $withdrawnAmount = $rowWithdraw['total_withdrawn'] ?? 0;
}

// Calculate Available Balance
$availableBalance = $totalAmount - $withdrawnAmount;

// Handle Withdrawal Request
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'withdraw') {
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    if ($amount && $amount > 0) {
        if ($amount <= $availableBalance) {
            $insertSql = "INSERT INTO withdrawals (restaurant_owner_id, amount, payment_method, status) VALUES ('$restaurantOwnerId', '$amount', '$payment_method', 'Pending')";
            if (mysqli_query($conn, $insertSql)) {
                $message = "Withdrawal request for $" . number_format($amount, 2) . " submitted successfully.";
                // Refresh balance
                $withdrawnAmount += $amount;
                $availableBalance -= $amount;
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        } else {
            $error = "Insufficient funds.";
        }
    } else {
        $error = "Invalid amount.";
    }
}
?>

<div style="max-width: 900px; margin: 0 auto; padding: 2rem 0;">

    <?php if ($message): ?>
        <div
            style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1rem;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div
            style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1rem;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Balance Card -->
        <div class="card"
            style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; padding: 2rem; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
            <i class="fas fa-wallet fa-3x" style="margin-bottom: 1rem; opacity: 0.8;"></i>
            <h2 style="font-weight: 400; opacity: 0.9; font-size: 1.2rem;">Available Balance</h2>
            <div style="font-size: 3rem; font-weight: 800; margin: 0.5rem 0;">
                $<?php echo number_format($availableBalance, 2); ?>
            </div>
            <p style="opacity: 0.8; font-size: 0.9rem;">Total Earnings: $<?= number_format($totalAmount, 2) ?></p>
        </div>

        <!-- Withdrawal Form -->
        <div class="card">
            <h3><i class="fas fa-university"></i> Request Withdrawal</h3>
            <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.5rem;">Transfer earnings to your account.
            </p>

            <form action="" method="post">
                <input type="hidden" name="action" value="withdraw">

                <div class="form-group">
                    <label>Payment Method</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <label
                            style="border: 1px solid var(--border-color); padding: 0.8rem; border-radius: var(--border-radius); cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-weight: normal;">
                            <input type="radio" name="payment_method" value="Bank Card" checked>
                            <span><i class="fas fa-credit-card"></i> Card</span>
                        </label>
                        <label
                            style="border: 1px solid var(--border-color); padding: 0.8rem; border-radius: var(--border-radius); cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-weight: normal;">
                            <input type="radio" name="payment_method" value="Mobile Wallet">
                            <span><i class="fas fa-mobile-alt"></i> Mobile</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="withdraw_amount">Amount ($)</label>
                    <input type="number" id="withdraw_amount" name="amount"
                        value="<?php echo $availableBalance > 0 ? $availableBalance : ''; ?>"
                        max="<?php echo $availableBalance; ?>" step="0.01" required placeholder="0.00"
                        style="font-weight: bold;">
                </div>

                <button type="submit" class="btn btn-primary btn-block" <?= $availableBalance <= 0 ? 'disabled' : '' ?>>
                    Submit Request
                </button>
            </form>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card" style="margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3><i class="fas fa-history"></i> Withdrawal History</h3>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                        <th style="padding: 1rem;">Date</th>
                        <th style="padding: 1rem;">Method</th>
                        <th style="padding: 1rem;">Amount</th>
                        <th style="padding: 1rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $historySql = "SELECT * FROM withdrawals WHERE restaurant_owner_id = '$restaurantOwnerId' ORDER BY created_at DESC LIMIT 10";
                    $historyResult = mysqli_query($conn, $historySql);

                    if (mysqli_num_rows($historyResult) > 0):
                        while ($txn = mysqli_fetch_assoc($historyResult)):
                            ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1rem; color: var(--text-muted);">
                                    <?= date('M d, Y h:i A', strtotime($txn['created_at'])) ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?= htmlspecialchars($txn['payment_method']) ?>
                                </td>
                                <td style="padding: 1rem; font-weight: 600;">
                                    $<?= number_format($txn['amount'], 2) ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($txn['status'] == 'Completed'): ?>
                                        <span style="color: var(--success-color); font-weight: 600;"><i
                                                class="fas fa-check-circle"></i> Completed</span>
                                    <?php elseif ($txn['status'] == 'Pending'): ?>
                                        <span style="color: #b08800; font-weight: 600;"><i class="fas fa-clock"></i> Pending</span>
                                    <?php else: ?>
                                        <span style="color: var(--danger-color); font-weight: 600;"><i
                                                class="fas fa-times-circle"></i> <?= htmlspecialchars($txn['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No withdrawal history found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="../dashboard/welcome.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<?php
mysqli_close($conn);
include '../includes/footer.php';
?>