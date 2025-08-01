CREATE DATABASE IF NOT EXISTS web_mobil_second;
USE web_mobil_second;

DROP TABLE IF EXISTS penjualan;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS car_images;
DROP TABLE IF EXISTS car_inspection_images;
DROP TABLE IF EXISTS car_types;
DROP TABLE IF EXISTS cars;
DROP TABLE IF EXISTS staff_pemasaran;
DROP TABLE IF EXISTS leasing_rules;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS showrooms;
DROP TABLE IF EXISTS daftarTypes;
DROP TABLE IF EXISTS daftarBrands;
DROP TABLE IF EXISTS daftarBahanBakar;


CREATE TABLE daftarBrands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    logo_url VARCHAR(222),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE daftarTypes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE showrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    opening_hours VARCHAR(200),
    status ENUM('active', 'renovation', 'opening_soon', 'permanent_closed') DEFAULT 'active',
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users (
    user_id VARCHAR(100) PRIMARY KEY,
    name TEXT NOT NULL,
    no_hp VARCHAR(20),
    password VARCHAR(255) NOT NULL, 
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin','customer') DEFAULT 'customer',
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE daftarBahanBakar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);
