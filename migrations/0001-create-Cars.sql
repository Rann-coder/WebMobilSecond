CREATE DATABASE IF NOT EXISTS web_mobil_second;
USE web_mobil_second;
DROP TABLE IF EXISTS car_types;
DROP TABLE IF EXISTS cars;
DROP TABLE IF EXISTS daftarBrands, daftarTypes;

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

CREATE TABLE cars (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_brand INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    `year` SMALLINT,
    price DECIMAL(15,2),
    image_url VARCHAR(255),
    description TEXT,
    specifications JSON,
    slug VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_brand) REFERENCES daftarBrands(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE car_types (
    car_id BIGINT NOT NULL,
    type_id INT NOT NULL,
    PRIMARY KEY (car_id, type_id),
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (type_id) REFERENCES daftarTypes(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO daftarBrands (name) VALUES
('Toyota'),
('Honda'),
('Mitsubishi Motors'),
('Suzuki'),
('Daihatsu'),
('Hyundai'),
('Wuling'),
('Chery'),
('BYD'),
('Mercedes-Benz'),
('BMW'),
('Mazda'),
('Nissan'),
('Audi'),
('Lexus'),
('Kia'),
('Subaru');

INSERT INTO daftarTypes (name) VALUES
('SUV'),
('MPV'),
('Sedan'),
('Hatchback'),
('Crossover'),
('Electric'),
('Hybrid'),
('LCGC'),
('City Car'),
('Luxury'),
('Coupe'),
('Convertible'),
('Diesel'),
('Van'),
('Pick-up');

-- Fixed INSERT statement: changed brand_id to id_brand
INSERT INTO cars (id_brand, name, `year`, price, image_url, description, specifications, slug) VALUES
(
    1, -- id_brand untuk Toyota
    'Avanza 1.5 G CVT',
    2024,
    272500000.00,
    'images/raize.png',
    'Generasi terbaru dari MPV terlaris di Indonesia, menawarkan desain modern dan fitur yang lebih canggih.',
    '{"engine": "1.5L 4-silinder", "transmission": "CVT", "horsepower": 106}',
    'toyota-avanza-1-5-g-cvt-2024'
),
(
    3, -- id_brand untuk Mitsubishi Motors
    'Pajero Sport Dakar Ultimate 4x2',
    2024,
    675600000.00,
    'images/raize.png',
    'SUV ladder-frame yang tangguh dan mewah, menjadi simbol status dengan performa diesel yang kuat.',
    '{"engine": "2.4L MIVEC Diesel", "transmission": "8-speed AT", "horsepower": 181}',
    'mitsubishi-pajero-sport-dakar-ultimate-4x2-2024'
),
(
    2, -- id_brand untuk Honda
    'Brio Satya E CVT',
    2025,
    198300000.00,
    'images/raize.png',
    'City car terpopuler dengan desain sporty dan performa lincah, menjadi pilihan utama anak muda.',
    '{"engine": "1.2L i-VTEC", "transmission": "CVT", "horsepower": 90}',
    'honda-brio-satya-e-cvt-2025'
),
(
    6, -- id_brand untuk Hyundai
    'Ioniq 5 Prime Standard Range',
    2024,
    782000000.00,
    'images/raize.png',
    'Mobil listrik murni dengan desain futuristik dan platform E-GMP yang canggih, menawarkan interior lega.',
    '{"engine": "Permanent Magnet Synchronous Motor", "transmission": "Single Speed Reduction Gear", "horsepower": 170}',
    'hyundai-ioniq-5-prime-sr-2024'
),
(
    4, -- id_brand untuk Suzuki
    'Ertiga Hybrid Cruise AT',
    2024,
    299000000.00,
    'images/raize.png',
    'MPV keluarga yang efisien berkat teknologi Suzuki Smart Hybrid, kini dengan tampilan yang lebih sporty.',
    '{"engine": "1.5L K15B + ISG", "transmission": "4-speed AT", "horsepower": 104}',
    'suzuki-ertiga-hybrid-cruise-at-2024'
);

INSERT INTO car_types (car_id, type_id) VALUES
-- Avanza (id=1) adalah MPV (id=2)
(1, 2),

-- Pajero Sport (id=2) adalah SUV (id=1) dan juga Diesel (id=13)
(2, 1),
(2, 13),

-- Brio (id=3) adalah Hatchback (id=4), LCGC (id=8), dan City Car (id=9)
(3, 4),
(3, 8),
(3, 9),

-- Ioniq 5 (id=4) adalah Crossover (id=5) dan juga Electric (id=6)
(4, 5),
(4, 6),

-- Ertiga (id=5) adalah MPV (id=2) dan juga Hybrid (id=7)
(5, 2),
(5, 7);