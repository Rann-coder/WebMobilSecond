CREATE TABLE cars (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    
    id_brand INT NOT NULL,
    showroom_id INT NULL, 

    name VARCHAR(150) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    year SMALLINT,
    price DECIMAL(15,2),
    color VARCHAR(50),
    license_plate VARCHAR(20),
    tax_valid_until DATE,
    
    km INT,
    engine_cc SMALLINT,
                       

    fuel_type ENUM('Gasoline', 'Diesel', 'Hybrid', 'Electric'),
    transmission VARCHAR(50),
    engine VARCHAR(100),
    horsepower SMALLINT,
    seat_count TINYINT,
    airbag_count TINYINT,
    
    status ENUM('Available', 'Hold', 'Sold Out') DEFAULT 'Available',
    approval_status ENUM('Pending', 'Reviewed', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    previous_owners TINYINT,

    is_accident_free ENUM('Yes', 'No', 'N/A') DEFAULT 'N/A',
    is_flood_free ENUM('Yes', 'No', 'N/A') DEFAULT 'N/A',
    
    hot_deal_label VARCHAR(50),
    image_url VARCHAR(255),
    description TEXT,
    specifications JSON,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (id_brand) REFERENCES daftarBrands(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (showroom_id) REFERENCES showrooms(id) ON DELETE SET NULL
);