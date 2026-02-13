<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Write a Review";
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../auth/login.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$customer_name = $_SESSION['username'];
$safe_name = mysqli_real_escape_string($conn, $customer_name);

// Verify order belongs to customer and is completed
$sql = "SELECT o.*, r.restaurant_name FROM orders o 
        JOIN restaurant_owners r ON o.restaurant_owner_id = r.id
        WHERE o.id = '$order_id' AND o.customer_name = '$safe_name' AND o.status = 'Completed'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<div class='container' style='padding-top: 2rem;'><div class='alert alert-danger'>Invalid order or order not completed.</div></div>";
    include '../includes/footer.php';
    exit;
}

$order = mysqli_fetch_assoc($result);

// Check if already reviewed
$checkReview = mysqli_query($conn, "SELECT id FROM reviews WHERE order_id = '$order_id'");
if (mysqli_num_rows($checkReview) > 0) {
    echo "<div class='container' style='padding-top: 2rem;'><div class='alert alert-info'>You have already reviewed this order.</div>
          <a href='my_orders.php' class='btn btn-primary'>Back to Orders</a></div>";
    include '../includes/footer.php';
    exit;
}
?>

<div class="container" style="max-width: 600px; padding-top: 2rem;">
    <div class="card">
        <h2 style="margin-bottom: 1.5rem; text-align: center;">Rate Your Experience</h2>
        <p class="text-muted" style="text-align: center; margin-bottom: 2rem;">
            How was your food from <strong>
                <?= htmlspecialchars($order['restaurant_name']) ?>
            </strong>?
        </p>

        <form action="process_review.php" method="post">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="restaurant_owner_id" value="<?= $order['restaurant_owner_id'] ?>">

            <div class="form-group" style="text-align: center; margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 1rem; font-size: 1.1rem;">Select Rating</label>
                <div class="rating-input"
                    style="display: flex; justify-content: center; gap: 1rem; flex-direction: row-reverse;">
                    <?php for ($i = 10; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required
                            style="display: none;">
                        <label for="star<?= $i ?>"
                            style="cursor: pointer; font-size: 1.5rem; color: #ddd; transition: color 0.2s;">
                            <i class="fas fa-star"></i>
                            <span style="display: block; font-size: 0.7rem; text-align: center;">
                                <?= $i ?>
                            </span>
                        </label>
                    <?php endfor; ?>
                </div>
                <style>
                    .rating-input input:checked~label,
                    .rating-input label:hover,
                    .rating-input label:hover~label {
                        color: #f1c40f !important;
                    }
                </style>
                <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.5rem;">(1 = Poor, 10 = Excellent)</p>
            </div>

            <div class="form-group">
                <label for="comment">Your Review</label>
                <textarea name="comment" id="comment" rows="4" class="form-control"
                    placeholder="Tell us what you liked or didn't like..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit Review</button>
            <a href="my_orders.php" class="btn btn-secondary btn-block"
                style="text-align: center; display: block; margin-top: 1rem; background: #eee; color: #333;">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>