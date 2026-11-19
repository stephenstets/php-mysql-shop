-- php-mysql-shop schema: a small product catalog.
-- Import with:  mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS shop
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop;

CREATE TABLE IF NOT EXISTS products (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)   NOT NULL,
    description TEXT           NOT NULL,
    price       DECIMAL(8,2)   NOT NULL,
    stock       INT UNSIGNED   NOT NULL DEFAULT 0,
    category    VARCHAR(50)    NOT NULL DEFAULT 'general',
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Sample products from the 5AM Cookies lineup.
INSERT INTO products (name, description, price, stock, category) VALUES
('Golf cart name plate',
 'Made-to-order name plate for a golf cart, raised two-color lettering.',
 35.00, 0, 'made-to-order'),
('Pet ID tag — circle',
 'Round pet ID tag with raised name and phone number, 4 mm split-ring hole.',
 12.00, 10, 'pet'),
('Pet ID tag — bone',
 'Bone-shaped pet ID tag with raised name and phone number.',
 12.00, 10, 'pet'),
('Golf ball marker',
 '25 mm marker with initials and an 8.5 x 2 mm magnet pocket.',
 8.00, 20, 'golf'),
('Phone stand',
 'Sturdy phone stand, prints with no supports.',
 12.00, 8, 'desk'),
('Hex coasters, set of 4',
 'Hexagonal coasters with a rim, set of four.',
 15.00, 6, 'home');
