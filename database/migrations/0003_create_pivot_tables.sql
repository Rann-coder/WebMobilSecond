CREATE TABLE car_types (
    car_id BIGINT NOT NULL,
    type_id INT NOT NULL,
    PRIMARY KEY (car_id, type_id),
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (type_id) REFERENCES daftarTypes(id) ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE car_showrooms (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    car_id BIGINT NOT NULL,
    showroom_id INT NOT NULL,
    stock_quantity INT DEFAULT 0,
    showroom_price DECIMAL(15,2), 
    is_available BOOLEAN DEFAULT TRUE,
    notes TEXT, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_car_showroom (car_id, showroom_id),
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (showroom_id) REFERENCES showrooms(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE car_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id BIGINT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(100),
    display_order TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);



CREATE TABLE leasing_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    leasing_name VARCHAR(100) NOT NULL UNIQUE,
    min_dp_percentage DECIMAL(5,2) NOT NULL, -- Minimum DP dalam persen (e.g., 20.00)
    max_dp_percentage DECIMAL(5,2) NOT NULL, -- Maksimum DP dalam persen
    admin_fee DECIMAL(15,2) NOT NULL,        -- Biaya administrasi
    interest_rate_1yr DECIMAL(5,2) NOT NULL, -- Suku bunga untuk 1 tahun
    interest_rate_2yr DECIMAL(5,2) NOT NULL, -- Suku bunga untuk 2 tahun
    interest_rate_3yr DECIMAL(5,2) NOT NULL,
    interest_rate_4yr DECIMAL(5,2) NOT NULL,
    interest_rate_5yr DECIMAL(5,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);