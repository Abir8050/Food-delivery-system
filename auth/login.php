<?php
session_start();
$page_title = "Login";
include '../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p class="text-muted">Login to manage your restaurant</p>
        </div>

        <?php if (isset($_SESSION['errors']['login'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['errors']['login']; ?>
            </div>
        <?php endif; ?>

        <form action="process_login.php" method="post" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username"
                    value="<?php echo isset($_SESSION['input']['username']) ? htmlspecialchars($_SESSION['input']['username']) : ''; ?>"
                    placeholder="Enter your username">
                <?php if (isset($_SESSION['errors']['username'])): ?>
                    <span class="error-msg"><?php echo $_SESSION['errors']['username']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••">
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <span class="error-msg"><?php echo $_SESSION['errors']['password']; ?></span>
                <?php endif; ?>
            </div>

            <div class="auth-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block">Sign In</button>
                <div style="margin-top: 1.5rem;">
                    <a href="register_selection.php">Create New Account</a><br>
                    <a href="forgot_password.php" style="font-size: 0.8rem; color: var(--text-muted);">Forgotten
                        password?</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
if (isset($_SESSION['input']))
    unset($_SESSION['input']);
include '../includes/footer.php';
?>