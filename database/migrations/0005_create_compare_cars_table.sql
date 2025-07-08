CREATE TABLE compare_cars (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    brand VARCHAR(100),
    year SMALLINT,
    price DECIMAL(15,2),
    image_url VARCHAR(255),
    engine_cc SMALLINT,
    km INT,
    transmission VARCHAR(50),
    fuel_type VARCHAR(50),
    previous_owners TINYINT
);
