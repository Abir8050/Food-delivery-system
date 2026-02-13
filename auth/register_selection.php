<?php
session_start();
$page_title = "Join Us";
include '../includes/header.php';
?>

<div class="auth-wrapper" style="align-items: center; min-height: 60vh;">
    <div class="card" style="width: 100%; max-width: 600px; text-align: center; padding: 3rem;">
        <h2 style="margin-bottom: 1rem; color: var(--primary-color);">Join Our Community</h2>
        <p class="text-muted" style="margin-bottom: 3rem; font-size: 1.1rem;">Choose how you want to get started</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Customer Option -->
            <a href="signup_customer.php" class="card"
                style="display: block; text-decoration: none; padding: 2rem; border: 2px solid transparent; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <i class="fas fa-user" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-color); margin-bottom: 0.5rem;">I want to Order</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Find delicious food from local restaurants</p>
            </a>

            <!-- Owner Option -->
            <a href="signup.php" class="card"
                style="display: block; text-decoration: none; padding: 2rem; border: 2px solid transparent; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <i class="fas fa-store" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-color); margin-bottom: 0.5rem;">I represent a Restaurant</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Partner with us to grow your business</p>
            </a>
        </div>

        <div style="margin-top: 2rem;">
            <p>Already have an account? <a href="login.php" style="font-weight: 600;">Log In</a></p>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1) !important;
        border-color: var(--primary-color) !important;
    }
</style>

<?php include '../includes/footer.php'; ?>