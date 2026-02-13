# FoodHome - Food Delivery Management System

A comprehensive web-based food delivery and restaurant management system built with PHP and MySQL. This application allows restaurant owners to manage their menus and orders, while customers can browse menus, place orders, and track their history.

![FoodHome Preview](https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80)
*(Note: Background illustration used in the app)*

## 🚀 Features

### For Restaurant Owners

- **Dashboard**: Overview of menu items, active orders, and reviews.
- **Menu Management**:
  - Add, Edit, and Delete menu items.
  - **Upload images** for each food item.
- **Order Management**: View incoming orders, change status (Pending -> Processing -> Completed/Cancelled).
- **Promotions**: Create and manage discount codes.
- **Profile**: Update restaurant details and password.

### For Customers

- **Visual Menu**: Browse food items with images, prices, and descriptions.
- **Ordering**: Place orders with "Cash on Delivery" or "Credit Card".
- **Order History**: Track the status of current and past orders.
- **Profile**: Manage personal account information.

## 🛠️ Technology Stack

- **Backend**: PHP (Native)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3 (Custom responsive design), JavaScript
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Inter)

## ⚙️ Installation & Setup

1. **Prerequisites**:
    - XAMPP (or any PHP/MySQL local server environment).

2. **Clone/Download**:
    - Place the project folder (e.g., `Food-management`) inside your server's root directory (e.g., `htdocs` for XAMPP).

3. **Database Setup**:
    - Open phpMyAdmin (`http://localhost/phpmyadmin`).
    - Create a new database named `food_delivery_system`.
    - Import the `db/food_delivery_system.sql` file located in the project folder.
    - *Update Note*: If you encounter "Unknown column image_path" errors, run the included helper script at `http://localhost/Food-management/update_db_schema.php` to patch your database.

4. **Configuration**:
    - If your database credentials differ from the defaults (User: `root`, Pass: ``), update `includes/db_connect.php`.

5. **Run**:
    - Open your browser and navigate to: `http://localhost/Food-management/`

## 📂 Project Structure

```
Food-management/
├── assets/          # CSS, Images, Uploads
├── auth/            # Login, Signup, Registration logic
├── customer/        # Customer-specific pages (Home, Orders)
├── dashboard/       # Restaurant Owner Dashboard & Stats
├── db/              # SQL Database file
├── includes/        # Header, Footer, DB Connection
├── menu/            # Menu Management (Add/Edit/View)
├── orders/          # Order Management for Owners
├── profile/         # User Profile pages
└── index.php        # Entry point (Redirects to Join Us)
```

## 🔐 Default Access (Sample Data)

If you imported the sample data:

**Restaurant Owner**:

- **Username**: `admin`
- **Password**: `admin123` *(Note: If login fails, create a new account via Sign Up)*

**Customer**:

- Create a new account via the "Join Us" page.

## ✨ Latest Updates

- **Image Support**: Added ability to upload rich food images.
- **Enhanced UI**: Improved background visibility and responsive cards.
- **Workflow**: Streamlined "Join Us" flow for new users.

---
*Created for the Food Delivery Management Project.*
