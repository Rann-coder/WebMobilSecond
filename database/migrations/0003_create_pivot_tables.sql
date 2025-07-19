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

CREATE TABLE car_inspection_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id BIGINT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) NULL,
    display_order TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (car_id) 
        REFERENCES cars(id) 
        ON DELETE CASCADE 
);

CREATE TABLE leasing_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    leasing_name VARCHAR(100) NOT NULL UNIQUE,
    min_dp_percentage DECIMAL(5,2) NOT NULL, 
    max_dp_percentage DECIMAL(5,2) NOT NULL,
    admin_fee DECIMAL(15,2) NOT NULL,        
    interest_rate_1yr DECIMAL(5,2) NOT NULL,
    interest_rate_2yr DECIMAL(5,2) NOT NULL, 
    interest_rate_3yr DECIMAL(5,2) NOT NULL,
    interest_rate_4yr DECIMAL(5,2) NOT NULL,
    interest_rate_5yr DECIMAL(5,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);




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


CREATE TABLE penjualan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_code VARCHAR(25) NOT NULL UNIQUE,
    
    car_id BIGINT NOT NULL,
    customer_id VARCHAR(255) NULL,
    guest_customer_name VARCHAR(100) NULL,
    showroom_id INT NOT NULL,
    staff_id INT NOT NULL,
    
    sale_date DATE NOT NULL,
    car_price_at_sale DECIMAL(15, 2) NOT NULL,
    admin_fee DECIMAL(10, 2) DEFAULT 0.00,
    discount DECIMAL(10, 2) DEFAULT 0.00,
    final_price DECIMAL(15, 2) NOT NULL,
    
    leasing_rule_id INT NULL,
    dp_amount DECIMAL(15,2) NULL,
    loan_tenor_years TINYINT NULL,
    monthly_installment DECIMAL(15,2) NULL,

    status ENUM('lunas', 'dp', 'hold') NOT NULL DEFAULT 'dp',
    notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE RESTRICT,
    FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (showroom_id) REFERENCES showrooms(id) ON DELETE RESTRICT,
    FOREIGN KEY (staff_id) REFERENCES staff_pemasaran(id) ON DELETE RESTRICT,
    FOREIGN KEY (leasing_rule_id) REFERENCES leasing_rules(id) ON DELETE SET NULL
);
