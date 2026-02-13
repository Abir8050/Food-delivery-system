<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Update Profile";
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM restaurant_owners WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h2><i class="fas fa-user-edit"></i> Update Profile</h2>
            <p class="text-muted">Keep your restaurant information current.</p>
        </div>

        <form action="process_update_profile.php" method="post" novalidate>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                <?php if (isset($_SESSION['errors']['email']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['email']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" name="contact" id="contact"
                    value="<?php echo htmlspecialchars($user['contact']); ?>">
                <?php if (isset($_SESSION['errors']['contact']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['contact']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="address">Business Address</label>
                <textarea name="address" id="address"
                    rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                <?php if (isset($_SESSION['errors']['address']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['address']}</span>"; ?>
            </div>

            <div
                style="background: #fff9db; padding: 1rem; border-radius: var(--border-radius); border-left: 4px solid #f59e0b; margin-bottom: 1.5rem;">
                <label for="password" style="color: #92400e;"><i class="fas fa-lock"></i> Verification Required</label>
                <p style="font-size: 0.8rem; color: #92400e; margin-bottom: 0.5rem;">Please enter your current password
                    to save changes.</p>
                <input type="password" name="password" id="password" placeholder="Confirm your password">
                <?php if (isset($_SESSION['errors']['password']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['password']}</span>"; ?>
                <?php if (isset($_SESSION['errors']['login']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['login']}</span>"; ?>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Save Changes</button>
                <a href="profile.php" class="btn"
                    style="flex: 1; background: #ecf0f1; border: 1px solid #bdc3c7;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
include '../includes/footer.php';
?>