<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
$page_title = "Menu Management";
include '../includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto; text-align: center;">
    <h2 style="margin-bottom: 2.5rem;">Restaurant Menu Management</h2>

    <div class="dashboard-grid">
        <a href="add_menu.php" class="card"
            style="display: block; padding: 3rem; border: 2px dashed var(--primary-color); background: rgba(46, 204, 113, 0.05); transition: transform 0.2s;">
            <i class="fas fa-plus-circle fa-4x" style="color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h3 style="color: var(--secondary-color);">Add New Item</h3>
            <p class="text-muted">Create a new dish or beverage for your customers.</p>
        </a>

        <a href="view_menu.php" class="card" style="display: block; padding: 3rem; transition: transform 0.2s;">
            <i class="fas fa-th-list fa-4x" style="color: var(--secondary-color); margin-bottom: 1.5rem;"></i>
            <h3 style="color: var(--secondary-color);">View All Items</h3>
            <p class="text-muted">Manage, edit, or remove existing menu items.</p>
        </a>
    </div>

    <div style="margin-top: 3rem;">
        <a href="../dashboard/welcome.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<?php
include '../includes/footer.php';
?>