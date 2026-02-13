<?php
session_start();
$page_title = "Join Us";
include '../includes/header.php';
?>

<div class="auth-wrapper" style="align-items: flex-start; padding-top: 50px;">
    <div class="card" style="width: 100%; max-width: 800px;">
        <div class="auth-header">
            <h2>Create Restaurant Account</h2>
            <p class="text-muted">Fill in the details to get started</p>
        </div>

        <form action="process_signup.php" method="post" novalidate>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Restaurant Info -->
                <div>
                    <h3
                        style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--primary-color); display: inline-block;">
                        Restaurant Details</h3>

                    <div class="form-group">
                        <label for="restaurantName">Restaurant Name</label>
                        <input type="text" name="restaurantName"
                            value="<?php echo isset($_SESSION['input']['restaurantName']) ? htmlspecialchars($_SESSION['input']['restaurantName']) : ''; ?>">
                        <?php if (isset($_SESSION['errors']['restaurantName']))
                            echo "<span class='error-msg'>{$_SESSION['errors']['restaurantName']}</span>"; ?>
                    </div>

                    <div class="form-group">
                        <label for="address">Full Address</label>
                        <textarea name="address"
                            rows="3"><?php echo isset($_SESSION['input']['address']) ? htmlspecialchars($_SESSION['input']['address']) : ''; ?></textarea>
                        <?php if (isset($_SESSION['errors']['address']))
                            echo "<span class='error-msg'>{$_SESSION['errors']['address']}</span>"; ?>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" name="contact"
                            value="<?php echo isset($_SESSION['input']['contact']) ? htmlspecialchars($_SESSION['input']['contact']) : ''; ?>">
                        <?php if (isset($_SESSION['errors']['contact']))
                            echo "<span class='error-msg'>{$_SESSION['errors']['contact']}</span>"; ?>
                    </div>
                </div>

                <!-- User Info -->
                <div>
                    <h3
                        style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--primary-color); display: inline-block;">
                        Owner Accounts</h3>

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

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password">
                            <?php if (isset($_SESSION['errors']['password']))
                                echo "<span class='error-msg'>{$_SESSION['errors']['password']}</span>"; ?>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm</label>
                            <input type="password" name="confirmPassword">
                            <?php if (isset($_SESSION['errors']['confirmPassword']))
                                echo "<span class='error-msg'>{$_SESSION['errors']['confirmPassword']}</span>"; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="securityQuestion">Security Question</label>
                        <select name="securityQuestion">
                            <option value="pet">What is the name of your first pet?</option>
                            <option value="birthCity">In what city were you born?</option>
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
                </div>
            </div>

            <div class="auth-footer" style="padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <button type="submit" name="submit" class="btn btn-primary" style="min-width: 200px;">Register
                    Account</button>
                <div style="margin-top: 1rem;">
                    <a href="login.php">Already have an account? Log In</a>
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