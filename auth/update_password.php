<?php
session_start();
$page_title = "Update Password";
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<div style="max-width: 500px; margin: 0 auto;">
    <div class="card">
        <div
            style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; text-align: center;">
            <i class="fas fa-user-lock fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
            <h2>Change Password</h2>
            <p class="text-muted">Ensure your account remains secure.</p>
        </div>

        <form action="process_update_password.php" method="post" novalidate>
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" name="currentPassword" id="currentPassword"
                    placeholder="Enter your current password">
                <?php if (isset($_SESSION['errors']['currentPassword']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['currentPassword']}</span>"; ?>
                <?php if (isset($_SESSION['errors']['currentPass']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['currentPass']}</span>"; ?>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="newPassword">New Password</label>
                <input type="password" name="newPassword" id="newPassword" placeholder="Minimum 4 characters">
                <?php if (isset($_SESSION['errors']['newPassword']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['newPassword']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="confirmNewPassword">Confirm New Password</label>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword"
                    placeholder="Repeat new password">
                <?php if (isset($_SESSION['errors']['confirmPassword']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['confirmPassword']}</span>"; ?>
                <?php if (isset($_SESSION['errors']['pass']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['pass']}</span>"; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 2rem;">Update Password</button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="welcome.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<?php
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
include '../includes/footer.php';
?>