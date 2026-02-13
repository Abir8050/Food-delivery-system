<?php
session_start();
include '../includes/header.php';
?>

<div class="auth-wrapper">
  <div class="auth-card">
    <div class="auth-header" style="text-align: center; margin-bottom: 2rem;">
      <i class="fas fa-key fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
      <h2>Recover Password</h2>
      <p class="text-muted">Verify your identity to reset your password</p>
    </div>

    <form action="process_forgot_password.php" method="post" novalidate>
      <div class="form-group">
        <label for="username"><i class="fas fa-user"></i> Username</label>
        <input type="text" name="username" id="username" placeholder="Enter your username">
        <?php if (isset($_SESSION['errors']['username']))
          echo "<span class='error-msg'>{$_SESSION['errors']['username']}</span>"; ?>
      </div>

      <div class="form-group">
        <label for="securityQuestion"><i class="fas fa-shield-alt"></i> Security Question</label>
        <select name="securityQuestion" id="securityQuestion">
          <option value="">-- Please choose an option --</option>
          <option value="pet" <?php if (isset($_SESSION['input']['securityQuestion']) && $_SESSION['input']['securityQuestion'] === 'pet')
            echo 'selected'; ?>>What is the name of your first pet?
          </option>
          <option value="birthCity" <?php if (isset($_SESSION['input']['securityQuestion']) && $_SESSION['input']['securityQuestion'] === 'birthCity')
            echo 'selected'; ?>>In what city were you born?
          </option>
        </select>
        <?php if (isset($_SESSION['errors']['securityQuestion']))
          echo "<span class='error-msg'>{$_SESSION['errors']['securityQuestion']}</span>"; ?>
      </div>

      <div class="form-group">
        <label for="securityAnswer"><i class="fas fa-comment-dots"></i> Security Answer</label>
        <input type="text" name="securityAnswer" id="securityAnswer"
          value="<?php echo isset($_SESSION['input']['securityAnswer']) ? $_SESSION['input']['securityAnswer'] : ''; ?>"
          placeholder="Type your answer here">
        <?php if (isset($_SESSION['errors']['securityAnswer']))
          echo "<span class='error-msg'>{$_SESSION['errors']['securityAnswer']}</span>"; ?>
      </div>

      <div class="form-group">
        <label for="newPassword"><i class="fas fa-lock"></i> New Password</label>
        <input type="password" name="newPassword" id="newPassword" placeholder="Minimum 4 characters">
        <?php if (isset($_SESSION['errors']['newPassword']))
          echo "<span class='error-msg'>{$_SESSION['errors']['newPassword']}</span>"; ?>
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Reset Password</button>
    </form>

    <div
      style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; justify-content: space-between; font-size: 0.9rem;">
      <a href="login.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Login</a>
      <a href="signup.php" style="color: var(--primary-color); font-weight: 600;">Create Account</a>
    </div>
  </div>
</div>

<?php
if (isset($_SESSION['errors']))
  unset($_SESSION['errors']);
if (isset($_SESSION['input']))
  unset($_SESSION['input']);
include '../includes/footer.php';
?>