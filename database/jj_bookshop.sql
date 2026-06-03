-- =====================================================
-- JJ BOOK SHOPPING - DATABASE SETUP
-- A beginner-friendly SQL file for XAMPP / phpMyAdmin
-- =====================================================

-- 1. Create the database
CREATE DATABASE IF NOT EXISTS jj_bookshop;
USE jj_bookshop;

-- 2. USERS TABLE
-- Stores registered customer accounts
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. ADMINS TABLE
-- Stores admin accounts for the dashboard
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. CATEGORIES TABLE
-- Stores book categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. BOOKS TABLE
-- Stores all books available in the shop
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    discount_price DECIMAL(10, 2),
    category_id INT,
    sku VARCHAR(50) UNIQUE,
    book_type VARCHAR(50),
    cover_image VARCHAR(255),
    stock INT DEFAULT 100,
    is_trendy TINYINT(1) DEFAULT 0,
    is_discount TINYINT(1) DEFAULT 0,
    is_bestseller TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 6. ORDERS TABLE
-- Stores customer orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100),
    email VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    payment_method VARCHAR(50),
    total_amount DECIMAL(10, 2),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 7. ORDER_ITEMS TABLE
-- Stores individual items in each order
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    book_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE SET NULL
);

-- 8. CART TABLE (for logged-in users)
-- Stores cart items for registered users
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Insert default admin account
-- Username: admin
-- Password: admin123 (hashed with password_hash in PHP)
INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@jjbookshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert default categories
INSERT INTO categories (name, icon, description) VALUES
('Programming', '💻', 'Books about coding, software development, and programming languages'),
('Novels', '📖', 'Fiction, stories, and literary works'),
('Education', '🎓', 'Textbooks, academic materials, and learning resources'),
('Technology', '⚡', 'Tech trends, gadgets, and digital innovation');

-- Insert sample books (using ETB prices - Ethiopian Birr)
INSERT INTO books (title, author, description, price, discount_price, category_id, sku, book_type, cover_image, stock, is_trendy, is_discount, is_bestseller) VALUES
('The Silent Gate', 'M.L. Thorne', 'A mysterious fantasy novel about a hidden realm behind an ancient gate.', 450.00, 380.00, 2, 'SG-001', 'Fantasy Novel', 'book1.jpg', 50, 1, 1, 1),
('Dragon Covenant', 'Elena Cross', 'Epic tale of dragons and the warriors sworn to protect their covenant.', 520.00, NULL, 2, 'DC-002', 'Fantasy Novel', 'book2.jpg', 40, 1, 0, 1),
('Echoes of the Fall', 'James Whitaker', 'Post-apocalyptic survival story with a haunting mystery.', 350.00, 290.00, 2, 'EF-003', 'Sci-Fi Novel', 'book3.jpg', 60, 1, 1, 0),
('Felicia Daye', 'Sarah Mitchell', 'Romantic drama exploring love, loss, and second chances.', 400.00, NULL, 2, 'FD-004', 'Romance Novel', 'book4.jpg', 45, 0, 0, 1),
('Finger Tips at Jwlight', 'David Chen', 'A thriller that keeps you on the edge of your seat until the last page.', 480.00, 420.00, 2, 'FJ-005', 'Thriller', 'book5.jpg', 35, 1, 1, 0),
('Shattered Truths', 'Amanda Frost', 'Political intrigue and personal betrayal collide in this gripping story.', 380.00, NULL, 2, 'ST-006', 'Drama', 'book6.jpg', 55, 0, 0, 1),
('Star Seed Draft', 'Neil Ruskin', 'Science fiction adventure across galaxies to save humanity.', 550.00, 480.00, 2, 'SD-007', 'Sci-Fi Novel', 'book7.jpg', 30, 1, 1, 1),
('The Echoes', 'Luna Vale', 'Supernatural mystery where past and present collide.', 410.00, NULL, 2, 'TE-008', 'Mystery', 'book8.jpg', 50, 1, 0, 0),
('The Moonswept', 'Orion Black', 'A poetic journey through moonlit landscapes and hidden dreams.', 360.00, 310.00, 2, 'TM-009', 'Poetry', 'book9.jpg', 40, 0, 1, 0),
('The Silence in The Crumbs', 'Grace Hollow', 'Psychological drama exploring the quiet moments that define us.', 430.00, NULL, 2, 'TS-010', 'Drama', 'book10.jpg', 45, 1, 0, 1),
('The Alley of Secretes', 'Robert Stone', 'Noir mystery set in the dark alleys of a rain-soaked city.', 470.00, 400.00, 2, 'AS-011', 'Mystery', 'book11.jpg', 35, 1, 1, 0);
