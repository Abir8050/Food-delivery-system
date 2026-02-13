<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Manage Menu";
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$query = "SELECT * FROM menus WHERE restaurant_owner_id = '$restaurantOwnerId'";
$result = mysqli_query($conn, $query);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><i class="fas fa-list"></i> Menu Management</h2>
        <a href="add_menu.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Item</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th style="width: 120px;">Price</th>
                    <th style="width: 120px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($row['id']) ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($row['name']) ?></td>
                            <td style="color: var(--text-muted); font-size: 0.9rem;">
                                <?= htmlspecialchars($row['description']) ?>
                            </td>
                            <td style="font-weight: 700; color: var(--primary-dark);">
                                $<?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
                            <td style="text-align: center;">
                                <a href="edit_menu.php?id=<?= $row['id'] ?>" class="btn btn-sm"
                                    style="background: #e0f2f1; color: #00897b; margin-right: 0.5rem;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_menu.php?id=<?= $row['id'] ?>" class="btn btn-sm"
                                    style="background: #fee2e2; color: #ef4444;"
                                    onclick="return confirm('Are you sure you want to delete this menu item?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-utensils fa-3x"
                                style="color: var(--border-color); margin-bottom: 1rem; display: block;"></i>
                            <p class="text-muted">No items in your menu yet.</p>
                            <a href="add_menu.php" class="btn btn-primary" style="margin-top: 1rem;">Add Your First Item</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        <a href="menu.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Menu Main</a>
    </div>
</div>

<?php
include '../includes/footer.php';
?>