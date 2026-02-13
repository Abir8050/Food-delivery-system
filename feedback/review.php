<?php
session_start();
include '../includes/db_connect.php';
$page_title = "Customer Reviews";
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$restaurant_owner_id = $_SESSION['user_id'];
$reviewQuery = "SELECT * FROM reviews WHERE restaurant_owner_id = ? ORDER BY review_date DESC";

$stmt = mysqli_prepare($conn, $reviewQuery);
mysqli_stmt_bind_param($stmt, 'i', $restaurant_owner_id);
mysqli_stmt_execute($stmt);
$reviewResult = mysqli_stmt_get_result($stmt);
?>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><i class="fas fa-star" style="color: #f1c40f;"></i> Customer Reviews</h2>
        <div class="text-muted">Total Reviews: <?php echo mysqli_num_rows($reviewResult); ?></div>
    </div>

    <?php if (mysqli_num_rows($reviewResult) > 0): ?>
        <?php while ($review = mysqli_fetch_assoc($reviewResult)): ?>
            <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h3 style="margin-bottom: 0.25rem;"><?php echo htmlspecialchars($review['customer_name']); ?></h3>
                        <div style="color: #f1c40f; margin-bottom: 0.5rem; font-weight: 700;">
                            <?php
                            // Assuming rating is out of 10 as per old code, convert to 5 stars for visual
                            $stars = floor($review['rating'] / 2);
                            for ($i = 0; $i < 5; $i++) {
                                echo $i < $stars ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                            }
                            ?>
                            <span
                                style="color: var(--text-muted); font-size: 0.8rem; margin-left: 0.5rem;">(<?php echo htmlspecialchars($review['rating']); ?>/10)</span>
                        </div>
                        <?php if (isset($review['order_id']) && $review['order_id']): ?>
                            <div style="font-size: 0.8rem; color: var(--primary-color); font-weight: 600;">
                                <i class="fas fa-receipt"></i> Order #<?= $review['order_id'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="text-muted"
                        style="font-size: 0.85rem;"><?= date('M d, Y', strtotime($review['review_date'])) ?></span>
                </div>

                <div
                    style="background: var(--bg-color); padding: 1rem; border-radius: var(--border-radius); border-left: 4px solid var(--primary-color); margin-top: 1rem; position: relative;">
                    <i class="fas fa-quote-left"
                        style="position: absolute; top: 0.5rem; left: 0.5rem; color: rgba(46, 204, 113, 0.2); font-size: 1.5rem;"></i>
                    <p style="padding-left: 1.5rem;"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                </div>

                <div
                    style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); background: #fbfbfb; margin: 1.5rem -1.5rem -1.5rem; padding: 1.5rem; border-bottom-left-radius: var(--border-radius); border-bottom-right-radius: var(--border-radius);">
                    <?php
                    // Check if feedback already exists
                    $f_stmt = mysqli_prepare($conn, "SELECT message FROM feedback WHERE review_id = ?");
                    mysqli_stmt_bind_param($f_stmt, 'i', $review['id']);
                    mysqli_stmt_execute($f_stmt);
                    $f_res = mysqli_stmt_get_result($f_stmt);
                    $f_data = mysqli_fetch_assoc($f_res);
                    mysqli_stmt_close($f_stmt);
                    ?>

                    <?php if ($f_data): ?>
                        <div style="display: flex; gap: 1rem;">
                            <i class="fas fa-reply" style="color: var(--primary-color); transform: scaleX(-1);"></i>
                            <div>
                                <span
                                    style="font-weight: 700; font-size: 0.85rem; color: var(--primary-dark); display: block; margin-bottom: 0.25rem;">YOUR
                                    RESPONSE</span>
                                <p style="font-size: 0.95rem; line-height: 1.4;">
                                    <?php echo nl2br(htmlspecialchars($f_data['message'])); ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <h4 style="font-size: 0.9rem; margin-bottom: 1rem;"><i class="fas fa-comment-dots"></i> Respond to this
                            review</h4>
                        <form action="submit_feedback.php" method="post" novalidate>
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <textarea name="message" rows="2" placeholder="Thank the customer or address their concerns..."
                                    style="font-size: 0.9rem;"></textarea>
                                <?php if (isset($_SESSION['errors']['message']))
                                    echo "<span class='error-msg'>{$_SESSION['errors']['message']}</span>"; ?>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Post Response</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card" style="text-align: center; padding: 4rem;">
            <i class="far fa-comments fa-4x" style="color: var(--border-color); margin-bottom: 1.5rem; display: block;"></i>
            <h3>No reviews yet</h3>
            <p class="text-muted">Once customers start reviewing your restaurant, they will appear here.</p>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="../dashboard/welcome.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<?php
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
mysqli_close($conn);
include '../includes/footer.php';
?>