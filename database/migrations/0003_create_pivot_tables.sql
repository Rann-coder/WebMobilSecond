CREATE TABLE car_types (
    car_id BIGINT NOT NULL,
    type_id INT NOT NULL,
    PRIMARY KEY (car_id, type_id),
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (type_id) REFERENCES daftarTypes(id) ON DELETE CASCADE ON UPDATE CASCADE
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


INSERT INTO daftarBahanBakar (name) VALUES
('Gasoline'),
('Diesel'),
('Hybrid'),
('Electric');

CREATE TABLE staff_pemasaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    telepon VARCHAR(20),
    foto_url VARCHAR(255),  
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    
    showroom_id INT NOT NULL, 

    FOREIGN KEY (showroom_id) 
        REFERENCES showrooms(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(100),
    car_id BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_car FOREIGN KEY (car_id) REFERENCES cars(id),
    CONSTRAINT unique_like UNIQUE (user_id, car_id)
);

