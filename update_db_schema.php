<?php
include 'includes/db_connect.php';

echo "<h2>Database Schema Updater</h2>";

// 1. Check/Add image_path to menus
echo "<h3>1. Checking 'menus' table...</h3>";
$query = "SHOW COLUMNS FROM menus LIKE 'image_path'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $alter = "ALTER TABLE menus ADD COLUMN image_path VARCHAR(255) AFTER price";
    if (mysqli_query($conn, $alter)) {
        echo "<p style='color: green;'>Success: Added <code>image_path</code> column.</p>";
    } else {
        echo "<p style='color: red;'>Error adding image_path: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>Column <code>image_path</code> already exists.</p>";
}

// 2. Check/Add created_at to promotional_offers
echo "<h3>2. Checking 'promotional_offers' table...</h3>";
$query = "SHOW COLUMNS FROM promotional_offers LIKE 'created_at'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $alter = "ALTER TABLE promotional_offers ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    if (mysqli_query($conn, $alter)) {
        echo "<p style='color: green;'>Success: Added <code>created_at</code> column.</p>";
    } else {
        echo "<p style='color: red;'>Error adding created_at: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>Column <code>created_at</code> already exists.</p>";
}

// 3. Create withdrawals table
echo "<h3>3. Checking 'withdrawals' table...</h3>";
$checkTable = "SHOW TABLES LIKE 'withdrawals'";
$result = mysqli_query($conn, $checkTable);

if (mysqli_num_rows($result) == 0) {
    $createTable = "CREATE TABLE withdrawals (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        restaurant_owner_id INT(11) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        status VARCHAR(20) DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (restaurant_owner_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    if (mysqli_query($conn, $createTable)) {
        echo "<p style='color: green;'>Success: Created <code>withdrawals</code> table.</p>";
    } else {
        echo "<p style='color: red;'>Error creating withdrawals table: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>Table <code>withdrawals</code> already exists.</p>";
}

echo "<hr>";
echo "<p>Database update process completed. You can now <a href='menu/view_menu.php'>return to Menu Management</a>.</p>";
?>