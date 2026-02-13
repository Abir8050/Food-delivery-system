<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
$page_title = "Add Menu Item";
include '../includes/header.php';
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h2><i class="fas fa-plus-circle"></i> Add New Menu Item</h2>
            <p class="text-muted">Enter the details of the dish you want to add.</p>
        </div>

        <form action="process_add_menu.php" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" name="name" id="name" placeholder="e.g. Grilled Chicken Salad"
                    value="<?php echo isset($_SESSION['input']['name']) ? htmlspecialchars($_SESSION['input']['name']) : ''; ?>">
                <?php if (isset($_SESSION['errors']['name']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['name']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"
                    placeholder="Describe the ingredients and preparation..."><?php echo isset($_SESSION['input']['description']) ? htmlspecialchars($_SESSION['input']['description']) : ''; ?></textarea>
                <?php if (isset($_SESSION['errors']['description']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['description']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="0.00"
                    value="<?php echo isset($_SESSION['input']['price']) ? htmlspecialchars($_SESSION['input']['price']) : ''; ?>">
                <?php if (isset($_SESSION['errors']['price']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['price']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="image">Menu Image</label>
                <input type="file" name="image" id="image" accept="image/*">
                <?php if (isset($_SESSION['errors']['image']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['image']}</span>"; ?>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Add to Menu</button>
                <a href="view_menu.php" class="btn"
                    style="flex: 1; background: #ecf0f1; border: 1px solid #bdc3c7;">Cancel</a>
            </div>
        </form>
    </div>

    <div style="text-align: center; margin-top: 1rem;">
        <a href="menu.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Menu Main</a>
    </div>
</div>

<?php
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
if (isset($_SESSION['input']))
    unset($_SESSION['input']);
include '../includes/footer.php';
?>