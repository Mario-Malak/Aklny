-- Create the database
CREATE DATABASE aklny;
USE aklny;

-- Create tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method ENUM('Cash','Visa') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);


-- Insert initial restaurants
INSERT INTO restaurants (name, description) VALUES
('Pizza Palace', 'Delicious pizzas made with fresh ingredients.'),
('Burger Bistro', 'Juicy burgers and crispy fries for every craving.');

-- Insert foods for Pizza Palace
INSERT INTO foods (restaurant_id, name, price) VALUES
(1, 'Margherita Pizza', 80.00),
(1, 'Pepperoni Pizza', 100.00),
(1, 'Veggie Pizza', 90.00);

-- Insert foods for Burger Bistro
INSERT INTO foods (restaurant_id, name, price) VALUES
(2, 'Classic Burger', 60.00),
(2, 'Cheese Burger', 70.00),
(2, 'Double Burger', 90.00);
