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