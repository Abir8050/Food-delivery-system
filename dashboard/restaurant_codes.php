<?php
session_start();
// Include header which handles DB connection
include '../includes/header.php';

$page_title = "Promotional Offers";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: ../auth/login.php');
    exit;
}

$restaurantOwnerId = $_SESSION['user_id'];
$sql = "SELECT * FROM promotional_offers WHERE restaurant_owner_id = '$restaurantOwnerId' ORDER BY created_at DESC";

// Auto-fix: Check if created_at exists, if not, ADD IT
$res_check = mysqli_query($conn, "SHOW COLUMNS FROM promotional_offers LIKE 'created_at'");
if (mysqli_num_rows($res_check) == 0) {
    mysqli_query($conn, "ALTER TABLE promotional_offers ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
}

$result = mysqli_query($conn, $sql);
?>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 2rem;">
    <!-- Create Offer Form -->
    <div class="card">
        <div style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem;">
            <h3><i class="fas fa-plus-circle"></i> Create New Offer</h3>
        </div>

        <form action="../process_discount.php" method="post" novalidate>
            <div class="form-group">
                <label for="name">Offer Name</label>
                <input type="text" id="name" name="name" placeholder="e.g. Weekend Special" required>
                <?php if (isset($_SESSION['errors']['name']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['name']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="What's included in this offer?"
                    required></textarea>
                <?php if (isset($_SESSION['errors']['description']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['description']}</span>"; ?>
            </div>

            <div class="form-group">
                <label for="discount_percent">Discount Percent (%)</label>
                <input type="number" id="discount_percent" name="discount_percent" min="1" max="100"
                    placeholder="e.g. 15" required>
                <?php if (isset($_SESSION['errors']['discount_percent']))
                    echo "<span class='error-msg'>{$_SESSION['errors']['discount_percent']}</span>"; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required>
                    <?php if (isset($_SESSION['errors']['start_date']))
                        echo "<span class='error-msg'>{$_SESSION['errors']['start_date']}</span>"; ?>
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required>
                    <?php if (isset($_SESSION['errors']['end_date']))
                        echo "<span class='error-msg'>{$_SESSION['errors']['end_date']}</span>"; ?>
                </div>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                <input type="checkbox" id="active" name="active" checked style="width: auto;">
                <label for="active" style="margin-bottom: 0;">Make this offer active immediately</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Launch Promotion</button>
        </form>
    </div>

    <!-- Active Offers List -->
    <div class="card">
        <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3><i class="fas fa-tags"></i> Your Current Offers</h3>
            <span class="text-muted" style="font-size: 0.85rem;">Active & Upcoming</span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 0.75rem; text-align: left;">Offer</th>
                        <th style="padding: 0.75rem; text-align: center;">Discount</th>
                        <th style="padding: 0.75rem; text-align: left;">Validity</th>
                        <th style="padding: 0.75rem; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 0.75rem;">
                                    <div style="font-weight: 600;">
                                        <?= htmlspecialchars($row['name']) ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 200px;">
                                        <?= htmlspecialchars($row['description']) ?>
                                    </div>
                                </td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    <span
                                        style="background: #fdf2f2; color: #cf222e; padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: 800; font-size: 0.85rem;">
                                        <?= htmlspecialchars($row['discount_percent']) ?>%
                                    </span>
                                </td>
                                <td style="padding: 0.75rem; font-size: 0.85rem;">
                                    <div style="display: flex; flex-direction: column;">
                                        <span><i class="far fa-calendar-alt" style="width: 15px; color: var(--text-muted);"></i>
                                            <?= date('M d', strtotime($row['start_date'])) ?>
                                        </span>
                                        <span><i class="far fa-calendar-check"
                                                style="width: 15px; color: var(--text-muted);"></i>
                                            <?= date('M d', strtotime($row['end_date'])) ?>
                                        </span>
                                    </div>
                                </td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    <?php if ($row['active']): ?>
                                        <span style="color: #1a7f37; font-size: 0.9rem;"><i class="fas fa-check-circle"></i>
                                            Active</span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.9rem;"><i
                                                class="fas fa-times-circle"></i> Inactive</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 3rem;">
                                <i class="fas fa-percentage fa-3x"
                                    style="color: var(--border-color); margin-bottom: 1rem; display: block;"></i>
                                <p class="text-muted">No promotional offers found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 2rem;">
    <a href="welcome.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<?php
if (isset($_SESSION['errors']))
    unset($_SESSION['errors']);
mysqli_close($conn);
include '../includes/footer.php';
?>