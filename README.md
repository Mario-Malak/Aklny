🍽️ Aklny - Simple Food Ordering System

Aklny is a modern PHP-based food ordering web application that allows users to browse restaurants, view menus, place orders, and choose between payment methods (Cash or Visa).
It’s built with PHP, MySQL, and HTML/CSS, with a clean, responsive UI.

Features:
User registration and login
Browse restaurants and food menus
Add items to order and review before checkout
Choose payment method (Cash / Visa)
View order history

Aklny Structure:
aklny/
config.php           # Database connection & session start
index.php            # Login page
register.php         # User registration
restaurants.php      # Restaurant and menu display
review_order.php     # Review & confirm order
orders.php           # User’s order history
logout.php           # Logout 
style.css            # Website styling
aklny.sql            # Database file

Setup Instructions in Linux:
1- Clone this repository
git clone https://github.com/Mario-Malak/Aklny

2-Move project to your server directory
sudo mv Aklny /var/www/html/

3-Create database
mysql -u root -p < /var/www/html/Aklny/aklny.sql

4-Start Apache and MySQL
sudo service apache2 start
sudo service mysql start

5- visit aklny in your browser
http://localhost/Aklny

ENJOY Using AKLNY :) 


