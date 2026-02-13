<?php
session_start();
$page_title = "Sign Up as Customer";
include '../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card card">
        <div class="auth-header">
            <h2>Create Customer Account</h2>
            <p class="text-muted">Order food from your favorite restaurants</p>
        </div>

        <form action="process_signup_customer.php" method="post" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username"
                    value="<?php echo isset($_SESSION['input']['username']) ? htmlspecialchars($_SESSION['input']['username']) : ''; ?>">
                <?php if (isset($_SESSION['errors']['username']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['username']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="text" name="email"
                    value="<?php echo isset($_SESSION['input']['email']) ? htmlspecialchars($_SESSION['input']['email']) : ''; ?>">
                <?php if (isset($_SESSION['errors']['email']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['email']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" name="contact"
                    value="<?php echo isset($_SESSION['input']['contact']) ? htmlspecialchars($_SESSION['input']['contact']) : ''; ?>">
                <?php if (isset($_SESSION['errors']['contact']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['contact']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="address">Delivery Address</label>
                <textarea name="address"
                    rows="2"><?php echo isset($_SESSION['input']['address']) ? htmlspecialchars($_SESSION['input']['address']) : ''; ?></textarea>
                <?php if (isset($_SESSION['errors']['address']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['address']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password">
                <?php if (isset($_SESSION['errors']['password']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['password']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" name="confirmPassword">
                <?php if (isset($_SESSION['errors']['confirmPassword']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['confirmPassword']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="securityQuestion">Security Question</label>
                <select name="securityQuestion">
                    <option value="pet">What is the name of your first pet?</option>
                    <option value="birthCity">In what city were you born?</option>
                    <option value="school">What was the name of your first school?</option>
                </select>
                <?php if (isset($_SESSION['errors']['securityQuestion']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['securityQuestion']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="securityAnswer">Security Answer</label>
                <input type="text" name="securityAnswer">
                <?php if (isset($_SESSION['errors']['securityAnswer']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['securityAnswer']}</span>"; ?>
            </div>

            <div class="auth-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block">Sign Up</button>
                <div style="margin-top: 1rem;">
                    <a href="login.php">Already have an account? Log In</a>
                </div>
                <div style="margin-top: 0.5rem;">
                    <a href="register_selection.php" class="text-muted" style="font-size: 0.85rem;">&larr; Back to Role
                        Selection</a>
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