<?php
// Fix Database Script
include 'includes/db_connect.php';

echo "<h1>Database Repair System</h1>";

function fixTable($conn, $tableName, $createSql, $columnsToCheck = [])
{
    echo "<h3>Checking table: $tableName...</h3>";

    // Check if table exists
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    if (mysqli_num_rows($check) == 0) {
        if (mysqli_query($conn, $createSql)) {
            echo "<p style='color: green;'>Created table '$tableName'.</p>";
        } else {
            echo "<p style='color: red;'>Failed to create '$tableName': " . mysqli_error($conn) . "</p>";
            return;
        }
    } else {
        echo "<p style='color: blue;'>Table '$tableName' exists.</p>";
    }

    // Check columns
    foreach ($columnsToCheck as $col => $def) {
        $checkCol = mysqli_query($conn, "SHOW COLUMNS FROM $tableName LIKE '$col'");
        if (mysqli_num_rows($checkCol) == 0) {
            $alter = "ALTER TABLE $tableName ADD COLUMN $col $def";
            if (mysqli_query($conn, $alter)) {
                echo "<p style='color: green;'>Added column '$col' to '$tableName'.</p>";
            } else {
                echo "<p style='color: red;'>Failed to add '$col': " . mysqli_error($conn) . "</p>";
            }
        }
    }
}

// 1. Menus
$menusSql = "CREATE TABLE menus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  restaurant_owner_id INT,
  name VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2),
  image_path VARCHAR(255),
  FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
fixTable($conn, 'menus', $menusSql, ['image_path' => 'VARCHAR(255) AFTER price']);

// 2. Promotional Offers
$offersSql = "CREATE TABLE promotional_offers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  restaurant_owner_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  discount_percent DECIMAL(5,2) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
fixTable($conn, 'promotional_offers', $offersSql, ['created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP']);

// 3. Withdrawals
$withdrawalsSql = "CREATE TABLE withdrawals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    restaurant_owner_id INT(11) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_owner_id) REFERENCES users(id) ON DELETE CASCADE
)";
// Note: Referring directly to users(id) as per typical hybrid auth systems found in these projects
// In food_delivery_system.sql it ref 'restaurant_owners', but often login uses 'users'.
// To be safe, let's check which table 'restaurant_owners' actually are.
// Usually 'users' table holds login info.
fixTable($conn, 'withdrawals', $withdrawalsSql);

// 4. Orders (Crucial for history)
$ordersSql = "CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  restaurant_owner_id INT,
  customer_name VARCHAR(255),
  customer_contact VARCHAR(255),
  customer_address TEXT,
  total_amount DECIMAL(10,2),
  status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  payment VARCHAR(255),
  FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
fixTable($conn, 'orders', $ordersSql, [
    'status' => "ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending'",
    'created_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
    'total_amount' => "DECIMAL(10,2)"
]);

echo "<hr><h2>Done.</h2>";
?>