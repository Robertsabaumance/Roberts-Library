# Library Project #

This is a PHP and MySQL library management system created for a college project.

Users can:

- Register and log in
- Search for books by title, author, or category
- Reserve available books
- View and return their reservations

# Technologies #

- PHP
- MySQL
- HTML
- CSS
- XAMPP

# How to Run #

1. Start Apache and MySQL in XAMPP.
2. Place the project folder in:

   'C:\xampp\htdocs\WebD\'

3. Create a MySQL database named 'book'.
4. Add the required tables and sample data using phpMyAdmin.
5. Open:

   'http://localhost/WebD/LibraryProject/homepage.php'

The database connection settings are stored in 'connection.php' and are configured for local XAMPP use.

# Files #

- connection.php - Connects to the MySQL database.
- header.php - Contains the shared header and navigation.
- footer.php - Contains the shared footer.
- homepage.php - Displays the welcome page.
- login.php - Allows users to log in.
- logout.php - Logs users out.
- registration.php - Allows new users to register.
- search.php - Allows users to search for books.
- reserve.php - Displays books and handles reservations.
- display_reservations.php - Displays book and reservation information.
- myReservations.php - Displays and manages the user's reservations.
- library.css - Contains the website styling.
- background1.jpg - Provides the website background.

# Purpose #

This project demonstrates PHP sessions, forms, password hashing, MySQL queries, user accounts, and book reservations.

It is for educational use only and is not intended for production. All sample users and database information are/should be fictional.
