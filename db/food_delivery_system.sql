CREATE DATABASE IF NOT EXISTS food_delivery_system
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE food_delivery_system;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/* =======================
   TABLE: restaurant_owners
   ======================= */
CREATE TABLE restaurant_owners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  restaurant_name VARCHAR(100) NOT NULL,
  address TEXT NOT NULL,
  contact VARCHAR(20) NOT NULL,
  securityQuestion VARCHAR(255) NOT NULL,
  securityAnswer VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: customers
   ======================= */
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  address TEXT NOT NULL,
  contact VARCHAR(20) NOT NULL,
  securityQuestion VARCHAR(255) NOT NULL,
  securityAnswer VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: menus
   ======================= */
CREATE TABLE menus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  restaurant_owner_id INT,
  name VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2),
  image_path VARCHAR(255),
  FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: orders
   ======================= */
CREATE TABLE orders (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: reviews
   ======================= */
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  restaurant_owner_id INT,
  order_id INT,
  customer_name VARCHAR(255),
  rating INT,
  comment TEXT,
  review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id),
  FOREIGN KEY (order_id) REFERENCES orders(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: feedback
   ======================= */
CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  review_id INT,
  restaurant_owner_id INT,
  message TEXT,
  feedback_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (review_id) REFERENCES reviews(id),
  FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: order_items
   ======================= */
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  menu_id INT,
  quantity INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (menu_id) REFERENCES menus(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: promotional_offers
   ======================= */
CREATE TABLE promotional_offers (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =======================
   TABLE: withdrawals
   ======================= */
CREATE TABLE withdrawals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    restaurant_owner_id INT(11) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_owner_id) REFERENCES restaurant_owners(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


COMMIT;
