<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: view_menu.php');
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch existing menu item safely
$query = "SELECT * FROM menus WHERE id = '$id' AND restaurant_owner_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    // Item not found or doesn't belong to user
    header('Location: view_menu.php');
    exit;
}

$menu_item = mysqli_fetch_assoc($result);

// Pre-fill session input if valid, otherwise use fetched data
// Priority: Session (if error occurred during edit) > DB Data
$current_name = isset($_SESSION['input']['name']) ? $_SESSION['input']['name'] : $menu_item['name'];
$current_desc = isset($_SESSION['input']['description']) ? $_SESSION['input']['description'] : $menu_item['description'];
$current_price = isset($_SESSION['input']['price']) ? $_SESSION['input']['price'] : $menu_item['price'];

$page_title = "Edit Menu Item";
include '../includes/header.php';
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h2><i class="fas fa-edit"></i> Edit Menu Item</h2>
            <p class="text-muted">Update the details of your menu item.</p>
        </div>

        <form action="process_edit_menu.php" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" name="name" id="name" placeholder="e.g. Grilled Chicken Salad"
                    value="<?php echo htmlspecialchars($current_name); ?>">
                <?php if (isset($_SESSION['errors']['name']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['name']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"
                    placeholder="Describe the ingredients and preparation..."><?php echo htmlspecialchars($current_desc); ?></textarea>
                <?php if (isset($_SESSION['errors']['description']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['description']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="0.00"
                    value="<?php echo htmlspecialchars($current_price); ?>">
                <?php if (isset($_SESSION['errors']['price']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['price']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="image">Menu Image</label>
                <?php if (!empty($menu_item['image_path'])): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <img src="../<?php echo htmlspecialchars($menu_item['image_path']); ?>" alt="Current Image"
                            style="max-width: 100px; max-height: 100px; border-radius: 4px; border: 1px solid #ddd;">
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Current Image</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" id="image" accept="image/*">
                <small class="text-muted">Leave empty to keep current image.</small>
                <?php if (isset($_SESSION['errors']['image']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['image']}</span>"; ?>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Update Item</button>
                <a href="view_menu.php" class="btn"
                    style="flex: 1; background: #ecf0f1; border: 1px solid #bdc3c7;">Cancel</a>
            </div>
        </form>
    </div>

    <div style="text-align: center; margin-top: 1rem;">
        <a href="view_menu.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to List</a>
    </div>
</div>

<?php
// Clear session messages
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
if (isset($_SESSION['input']))
    unset($_SESSION['input']);
include '../includes/footer.php';
?>