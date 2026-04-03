# CP476 - Product Review and Rating System

## Overview:
A full-stack web application that allows users to add products, submit reviews and ratings, and view aggregated ratings. This project serves as the capstone for CP476 - Internet Computing at Wilfrid Laurier University.

## Features:
- Add products to a database
- Upload product images
- View products on the homepage and their details
- Submit reviews and ratings
- View aggregated ratings
- Delete produccts from the system

## Front End:
- `index.php` displays all products and their average ratings
- `add_product.php` allows users to add a new product
- `product.php` displays a product’s details, image, and reviews
- `add_review.php` allows users to submit a rating and review
- `styles.css` has the styling and layout for the application
- Designed so users can navigate easily between all screens

## Back End:
- PHP is used for server-side logic and form handling
- MySQL is used to store product and review data
- `db.php` handles the database connection
- Product and review data are retrieved and inserted using SQL queries
- Prepared statements are used for safer database operations
- Server-side validation is included for required fields and valid rating input
- Product image uploads are stored in the `uploads` folder and referenced in the database

## Tech Stack:
- Front End: HTML, CSS
- Back End: PHP
- Database: MySQL
- Local Server: XAMPP (Apache + MySQL)

## Project Members:
Jordan Asmono
Marushen Baskaran 
Parker Riches 

## Team contributions shown on Wiki

## Setup Instructions

1. Clone this repository.
2. Install XAMPP.
3. Move the project folder into your XAMPP `htdocs` folder.
4. Start Apache and MySQL in XAMPP.
5. Open phpMyAdmin at: `http://localhost/phpMyAdmin'
6. Import the `database.sql` file.
7. Make sure the database connection settings in `db.php` match your local MySQL port.
8. Open the project in your browser.

## Example
- If project folder is named Product-Review-and-Rating-System open the link: 'http://localhost/Product-Review-and-Rating-System/public/index.php'

## Notes
- The product name is required to submit
- Ratings are limited to values between 1 and 5
- Only valid image types are accepted to be uploaded
- Uploaded images are stored in the uploads folder
- If no iamge is uploaded, a default iamge is displayed
- The Project is expected to be run locally after installation of XAMPP