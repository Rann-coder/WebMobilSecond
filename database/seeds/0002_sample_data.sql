














-- Fixed INSERT statement: changed brand_id to id_brand
INSERT INTO cars (id_brand, name, year, price, image_url, description, specifications, slug) VALUES
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
),
(5, -- id_brand untuk Daihatsu
    'Rocky 1.0 R TC CVT ASA',
    2024,
    273450000.00,
    'images/raize.png',
    'SUV kompak dengan mesin turbo yang responsif dan fitur keselamatan canggih, Advanced Safety Assist (ASA).',
    '{"engine": "1.0L Turbo 3-silinder", "transmission": "D-CVT", "horsepower": 98}',
    'daihatsu-rocky-1-0-r-tc-cvt-asa-2024'
),
(
    7, -- id_brand untuk Wuling
    'Air EV Long Range',
    2024,
    299500000.00,
    'images/raize.png',
    'Mobil listrik mungil untuk perkotaan yang praktis dan mudah dikendarai, dengan jarak tempuh hingga 300 km.',
    '{"engine": "Permanent Magnet Synchronous Motor", "transmission": "Single Speed Reduction Gear", "horsepower": 40}',
    'wuling-air-ev-long-range-2024'
),
(
    1, -- id_brand untuk Toyota
    'Kijang Innova Zenix G Hybrid CVT',
    2024,
    477600000.00,
    'images/raize.png',
    'Revolusi dari Kijang Innova yang kini menggunakan platform monokok TNGA-C dan teknologi hybrid generasi kelima.',
    '{"engine": "2.0L M20A-FXS Hybrid", "transmission": "e-CVT", "horsepower": 186}',
    'toyota-kijang-innova-zenix-g-hybrid-2024'
),
(
    2, -- id_brand untuk Honda
    'HR-V 1.5L Turbo RS',
    2024,
    540300000.00,
    'images/raize.png',
    'Compact SUV dengan desain coupe yang stylish dan performa mesin turbo yang bertenaga untuk pengalaman berkendara yang menyenangkan.',
    '{"engine": "1.5L VTEC Turbo", "transmission": "CVT", "horsepower": 177}',
    'honda-hr-v-1-5l-turbo-rs-2024'
),
(
    11, -- id_brand untuk BMW
    '320i M Sport',
    2024,
    1165000000.00,
    'images/raize.png',
    'Sedan sport legendaris yang mendefinisikan kenikmatan berkendara. Kombinasi sempurna antara kemewahan, teknologi, dan performa.',
    '{"engine": "2.0L BMW TwinPower Turbo", "transmission": "8-speed Steptronic", "horsepower": 184}',
    'bmw-320i-m-sport-2024'
),
(
    8, -- id_brand untuk Chery
    'Omoda 5 RZ',
    2024,
    404900000.00,
    'images/raize.png',
    'Crossover futuristik dengan desain "Art in Motion" yang berani dan fitur-fitur canggih di kelasnya.',
    '{"engine": "1.5L Turbo", "transmission": "9-speed CVT", "horsepower": 145}',
    'chery-omoda-5-rz-2024'
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

-- Ioniq 5 (id=4) adalah Crossover (id=5) dan juga Electric (id=6-)
(4, 5),
(4, 6),

-- Ertiga (id=5) adalah MPV (id=2) dan juga Hybrid (id=7)
(5, 2),
(5, 7),

(1, 4), -- Avanza = MPV
(3, 2), -- Civic = Sedan
(4, 1), -- CR-V = SUV
(5, 1),
(6, 1),
(6, 5),

-- Wuling Air EV (id=7) adalah Electric (id=6) dan City Car (id=9)
(7, 6),
(7, 9),

-- Kijang Innova Zenix (id=8) adalah MPV (id=2), Hybrid (id=7), dan bisa dianggap Luxury (id=10)
(8, 2),
(8, 7),
(8, 10),

-- Honda HR-V (id=9) adalah SUV (id=1) dan Crossover (id=5)
(9, 1),
(9, 5),

-- BMW 320i (id=10) adalah Sedan (id=3) dan Luxury (id=10)
(10, 3),
(10, 10),

-- Chery Omoda 5 (id=11) adalah Crossover (id=5) dan juga SUV (id=1)
(11, 5),
(11, 1); -- BMW X3 = SUV

INSERT INTO showrooms (id, name, address, phone, email, manager_name) VALUES
(1, 'Showroom Medan Jaya', 'Jl. Gatot Subroto No. 123, Medan', '061-123456', 'sales@medanjaya.com', 'Budi Santoso'),
(2, 'Sumatra Auto Gallery', 'Jl. Sisingamangaraja No. 45, Medan', '061-654321', 'info@sumatraauto.com', 'Citra Lestari');

INSERT INTO car_showrooms (car_id, showroom_id, stock_quantity, showroom_price, is_available) VALUES 
-- Showroom A inventory
(1, 1, 3, 250000000.00, TRUE),  -- Avanza di Showroom A
(2, 1, 2, 550000000.00, TRUE),  -- Fortuner di Showroom A
(3, 1, 1, 650000000.00, TRUE),  -- Civic di Showroom A
(4, 1, 0, 750000000.00, FALSE), -- CR-V di Showroom A (out of stock)

-- Showroom B inventory
(1, 2, 5, 248000000.00, TRUE),  -- Avanza di Showroom B (harga beda)
(3, 2, 2, 645000000.00, TRUE),  -- Civic di Showroom B (harga beda)
(4, 2, 3, 745000000.00, TRUE),  -- CR-V di Showroom B
(5, 2, 1, 1200000000.00, TRUE); -- BMW X3 di Showroom B saja

INSERT INTO users (user_id, name, no_hp, password, email, role, alamat)VALUES 
('USR001', 'Bryan Cen', '08123456789', 'admin01', 'bryancenbryan@gmail.com', 'customer', 'Jl. Melati No. 1'),
('USR002', 'Randy', '08121234567', 'staff01', 'randy@gmail.com', 'admin', 'Jl. Mawar No. 10'),
('USR003', 'Budi Hartono', '08129876543', 'tester', 'budi@example.com', 'customer', 'Jl. Kenanga No. 5');
