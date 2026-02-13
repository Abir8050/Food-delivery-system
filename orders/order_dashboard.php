<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Orders Dashboard";
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE restaurant_owner_id = '$restaurantOwnerId' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="card">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2><i class="fas fa-shopping-bag"></i> Orders Dashboard</h2>
    <div class="text-muted">Total Orders: <?php echo mysqli_num_rows($result); ?></div>
  </div>

  <div style="overflow-x: auto;">
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Date</th>
          <th>Total</th>
          <th>Status</th>
          <th>Payment</th>
          <th style="text-align: center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)):
            $status_class = '';
            switch ($row['status']) {
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
            <tr>
              <td style="font-weight: 600;">#<?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['customer_name']) ?></td>
              <td style="font-size: 0.85rem; color: var(--text-muted);">
                <?= date('M d, Y h:i A', strtotime($row['created_at'])) ?>
              </td>
              <td style="font-weight: 700;">$<?= htmlspecialchars(number_format($row['total_amount'], 2)) ?></td>
              <td>
                <span
                  style="padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; <?= $status_class ?>">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($row['payment']) ?></td>
              <td>
                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                  <a href="order_details.php?id=<?= $row['id'] ?>" class="btn btn-sm"
                    style="background: var(--bg-color); color: var(--secondary-color);" title="View Details">
                    <i class="fas fa-eye"></i>
                  </a>

                  <?php if ($row['status'] == 'Pending'): ?>
                    <a href="process_order.php?id=<?= $row['id'] ?>&action=process" class="btn btn-sm"
                      style="background: #e0f2fe; color: #0369a1;" onclick="return confirm('Process this order?')">
                      Process
                    </a>
                  <?php endif; ?>

                  <?php if ($row['status'] == 'Processing'): ?>
                    <a href="process_order.php?id=<?= $row['id'] ?>&action=complete" class="btn btn-sm"
                      style="background: #dcfce7; color: #15803d;" onclick="return confirm('Mark as Completed?')">
                      Complete
                    </a>
                  <?php endif; ?>

                  <?php if ($row['status'] != 'Cancelled' && $row['status'] != 'Completed'): ?>
                    <a href="process_order.php?id=<?= $row['id'] ?>&action=cancel" class="btn btn-sm"
                      style="background: #fee2e2; color: #b91c1c;" onclick="return confirm('Cancel this order?')">
                      Cancel
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 3rem;">
              <i class="fas fa-box-open fa-3x"
                style="color: var(--border-color); margin-bottom: 1rem; display: block;"></i>
              <p class="text-muted">No orders found.</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div style="text-align: center; margin-top: 1.5rem;">
  <a href="../dashboard/welcome.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<?php
include '../includes/footer.php';
?>