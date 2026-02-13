<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = "Delicious Food Menu";
include '../includes/header.php';


// Fetch all menus with restaurant details AND active discount
// We subquery to find the best active discount for the restaurant
$query = "SELECT m.*, r.restaurant_name, r.address,
          (SELECT MAX(discount_percent) FROM promotional_offers po 
           WHERE po.restaurant_owner_id = m.restaurant_owner_id 
           AND po.active = 1 
           AND CURDATE() BETWEEN po.start_date AND po.end_date) as discount_percent
          FROM menus m 
          JOIN restaurant_owners r ON m.restaurant_owner_id = r.id 
          ORDER BY m.id DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed during diagnosis: " . mysqli_error($conn)); // Debugging aid
}
?>

<div class="container">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="color: var(--primary-dark); margin-bottom: 0.5rem;">Explore Our Menu</h1>
        <p class="text-muted">Order from the best restaurants in town</p>
    </div>

    <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)):
                $originalPrice = $row['price'];
                $discount = $row['discount_percent'];
                $hasDiscount = !empty($discount) && $discount > 0;
                $finalPrice = $hasDiscount ? $originalPrice - ($originalPrice * $discount / 100) : $originalPrice;
                ?>
                <div class="card"
                    style="padding: 0; overflow: hidden; display: flex; flex-direction: column; position: relative;">
                    <?php if ($hasDiscount): ?>
                        <div
                            style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 5px 10px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 10;">
                            <?= htmlspecialchars(number_format($discount)) ?>% OFF
                        </div>
                    <?php endif; ?>

                    <!-- Placeholder Image (Generated or Static) -->
                    <div
                        style="height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <?php
                        $imgSrc = !empty($row['image_path']) ? '../' . $row['image_path'] : "https://source.unsplash.com/400x300/?food," . urlencode($row['name']);
                        ?>
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"
                            style="width: 100%; height: 100%; object-fit: cover;"
                            onerror="this.src='https://placehold.co/400x300?text=Delicious+Food'">
                    </div>

                    <div style="padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <h3 style="margin: 0; font-size: 1.25rem;">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </h3>
                            <div style="text-align: right;">
                                <?php if ($hasDiscount): ?>
                                    <span
                                        style="display: block; font-size: 0.9rem; color: var(--text-muted); text-decoration: line-through;">
                                        $<?php echo htmlspecialchars(number_format($originalPrice, 2)); ?>
                                    </span>
                                    <span
                                        style="background: var(--primary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: bold; display: inline-block;">
                                        $<?php echo htmlspecialchars(number_format($finalPrice, 2)); ?>
                                    </span>
                                <?php else: ?>
                                    <span
                                        style="background: var(--primary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: bold;">
                                        $<?php echo htmlspecialchars(number_format($originalPrice, 2)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem; flex-grow: 1;">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </p>

                        <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: auto;">
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                                <i class="fas fa-store"></i>
                                <?php echo htmlspecialchars($row['restaurant_name']); ?>
                            </p>

                            <a href="place_order_customer.php?menu_id=<?php echo $row['id']; ?>"
                                class="btn btn-primary btn-block">
                                Order Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <p>No menu items available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>