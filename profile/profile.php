<?php
session_start();
include '../includes/db_connect.php';
$page_title = "My Profile";
include '../includes/header.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'owner'; // Default to owner if not set, but it should be

if ($role === 'customer') {
    $query = "SELECT * FROM customers WHERE id = '$user_id'";
} else {
    $query = "SELECT * FROM restaurant_owners WHERE id = '$user_id'";
}

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);
?>

<div style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h2><i class="fas fa-user-circle"></i> Profile Information</h2>
            <a href="update_profile.php" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit Profile</a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
            <div style="border-right: 1px solid var(--border-color); padding-right: 1.5rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div
                        style="width: 100px; height: 100px; background: var(--bg-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 2px solid var(--primary-color);">
                        <i class="fas fa-store fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h3 style="color: var(--primary-dark);">
                        <?php 
                        if (isset($user['restaurant_name'])) {
                            echo htmlspecialchars($user['restaurant_name']); 
                        } else {
                            echo htmlspecialchars($user['username']);
                        }
                        ?>
                    </h3>
                    <p class="text-muted">ID: #<?php echo $user['id']; ?></p>
                </div>
            </div>

            <div>
                <table class="profile-table">
                    <tr>
                        <th style="border: none; background: transparent; width: 150px; color: var(--text-muted);">
                            Username</th>
                        <td style="border: none; font-weight: 600;"><?php echo htmlspecialchars($user['username']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="border: none; background: transparent; color: var(--text-muted);">Email</th>
                        <td style="border: none; font-weight: 600;"><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <th style="border: none; background: transparent; color: var(--text-muted);">Contact</th>
                        <td style="border: none; font-weight: 600;"><?php echo htmlspecialchars($user['contact']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="border: none; background: transparent; color: var(--text-muted);">Address</th>
                        <td style="border: none; font-weight: 600;">
                            <?php echo nl2br(htmlspecialchars($user['address'])); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 1rem;">
        <a href="../dashboard/welcome.php" style="color: var(--text-muted); font-weight: 500;">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php
include '../includes/footer.php';
?>