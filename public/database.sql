CREATE DATABASE IF NOT EXISTS product_review_system;
USE product_review_system;

DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    rating INT NOT NULL,
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO products (name, description, category, image) VALUES
('iPhone 16 Pro Max', 'A premium smartphone with a large display, advanced camera system, and high performance.', 'Technology', 'iphone16.png'),
('Sony WH-1000XM5', 'Wireless noise-cancelling headphones with long battery life and high-quality sound.', 'Electronics', 'sonyxm5.png'),
('Kindle Paperwhite', 'A lightweight e-reader with a glare-free display and adjustable warm light.', 'Books & Reading', 'kindle.png'),
('Nike Air Force 1', 'Classic everyday sneakers with a timeless design and comfortable fit.', 'Fashion', 'airforce1.png');

INSERT INTO reviews (product_id, rating, review_text) VALUES
(1, 5, 'Amazing phone with a great camera and battery life.'),
(1, 4, 'Very fast and smooth, but a bit expensive.'),
(2, 5, 'Excellent noise cancellation and very comfortable.'),
(2, 4, 'Sound quality is great and the battery lasts a long time.'),
(3, 4, 'Perfect for reading, especially at night.'),
(4, 5, 'Very comfortable and stylish for daily wear.');