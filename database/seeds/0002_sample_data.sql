INSERT INTO showrooms (id, name, address, phone, email, status, opening_hours, image_url) VALUES
(1, 'Showroom Medan Abadi - Gatsu', 'Jl. Gatot Subroto No. 123, Medan', '061-123456', 'sales@medanjaya.com', 'active', 'Senin - Sabtu: 09:00 - 18:00', 'images/showrooms/showroomA.jpg'),
(2, 'Showroom Medan Abadi - Sisimangaraja', 'Jl. Sisingamangaraja No. 45, Medan', '061-654321', 'info@sumatraauto.com', 'active', 'Senin - Sabtu: 09:00 - 18:00', 'images/showrooms/showroomA.jpg'),
(3, 'Showroom Medan Abadi - Nibung Raya', 'Jl. Nibung Raya No. 78, Medan', '061-789012', 'contact@majurenovasi.com', 'renovation', NULL, 'images/showrooms/showroomA.jpg');


INSERT INTO cars (
    id_brand, showroom_id, name, year, price, km, 
    engine_cc, fuel_type, previous_owners, 
    license_plate, tax_valid_until, color, seat_count, airbag_count,
    is_accident_free, is_flood_free,
    engine, transmission, horsepower, status, approval_status,
    image_url, description, slug
) VALUES
(
    1, 1, 'Avanza 1.5 G CVT', 2023, 272500000.00, 15000,
    13252, 'Gasoline', 1, 'BK 1234 AA', '2025-08-20', 'White', 7, 2, 'Yes', 'Yes', 
    '1.5L 4-silinder', 'CVT', 106, 'Available', 'Approved', 
    '../../images/cars/fisik/avanza-tampak-serong.png', 'Generasi terbaru dari MPV terlaris di Indonesia.',  'toyota-avanza-1-5-g-cvt-2023-1'
),
(
    3, 2, 'Pajero Sport Dakar Ultimate 4x2', 2024, 675600000.00, 22000,
    2400, 'Diesel', 1, 'BK 5678 BB', '2025-05-15', 'Black', 7, 7, 'Yes', 'Yes', 
    '2.4L MIVEC Diesel', '8-speed AT', 181, 'Available', 'Approved', 
    '../../images/raize.png', 'SUV ladder-frame yang tangguh dan mewah.',  'mitsubishi-pajero-sport-dakar-ultimate-4x2-2024-2'
),
(
    2, 1, 'Brio Satya E CVT', 2025, 198300000.00, 5500,
    1200, 'Gasoline', 1, 'BK 9101 CC', '2026-01-30', 'Red', 5, 2, 'No', 'Yes', 
    '1.2L i-VTEC', 'CVT', 90, 'Hold', 'Pending',
    '../../images/raize.png', 'City car terpopuler dengan desain sporty.', 'honda-brio-satya-e-cvt-2025-3'
),
(
    6, 2, 'Ioniq 5 Prime Standard Range', 2024, 782000000.00, 12000,
    0, 'Electric', 1, 'B 2024 EV', '2025-11-05', 'Silver', 5, 6, 'Yes', 'Yes', 
    'Permanent Magnet Synchronous Motor', 'Single Speed Reduction Gear', 170, 'Available', 'Approved',
    '../../images/raize.png', 'Mobil listrik murni dengan desain futuristik.', 'hyundai-ioniq-5-prime-sr-2024-4'
),
(
    4, 1, 'Ertiga Hybrid Cruise AT', 2024, 299000000.00, 9800,
    1500, 'Hybrid', 1, 'BK 1122 DD', '2025-09-10', 'Gray', 7, 2, 'Yes', 'Yes', 
    '1.5L K15B + ISG', '4-speed AT', 104, 'Sold Out', 'Rejected', 
    '../../images/raize.png', 'MPV keluarga yang efisien berkat teknologi hybrid.', 'suzuki-ertiga-hybrid-cruise-at-2024-5'
),
(
    5, 2, 'Rocky 1.0 R TC CVT ASA', 2024, 273450000.00, 18000,
    1000, 'Gasoline', 1, 'BK 3344 EE', '2025-07-22', 'Yellow', 5, 6, 'Yes', 'Yes', 
    '1.0L Turbo 3-silinder', 'D-CVT', 98, 'Available', 'Reviewed', 
    '../../images/raize.png', 'SUV kompak dengan mesin turbo yang responsif.', 'daihatsu-rocky-1-0-r-tc-cvt-asa-2024-6'
),
(
    7, 1, 'Air EV Long Range', 2024, 299500000.00, 7000,
    0, 'Electric', 1, 'BK 5566 FF', '2025-12-01', 'White', 4, 2, 'Yes', 'Yes', 
    'Permanent Magnet Synchronous Motor', 'Single Speed Reduction Gear', 40, 'Available', 'Approved', 
    '../../images/raize.png', 'Mobil listrik mungil untuk perkotaan yang praktis.','wuling-air-ev-long-range-2024-7'
),
(
    1, 2, 'Kijang Innova Zenix G Hybrid CVT', 2024, 477600000.00, 25000,
    2000, 'Hybrid', 1, 'BK 7788 GG', '2025-04-18', 'Black', 7, 6, 'Yes', 'No', 
    '2.0L M20A-FXS Hybrid', 'e-CVT', 186, 'Available', 'Approved', 
    '../../images/raize.png', 'Revolusi dari Kijang Innova dengan teknologi hybrid.', 'toyota-kijang-innova-zenix-g-hybrid-2024-8'
),
(
    2, 1, 'HR-V 1.5L Turbo RS', 2024, 540300000.00, 16500,
    1500, 'Gasoline', 1, 'BK 9900 HH', '2025-06-25', 'Red', 5, 6, 'Yes', 'Yes', 
    '1.5L VTEC Turbo', 'CVT', 177, 'Hold', 'Reviewed', 
    '../../images/raize.png', 'Compact SUV dengan desain coupe yang stylish.', 'honda-hr-v-1-5l-turbo-rs-2024-9'
),
(
    11, 2, '320i M Sport', 2024, 1165000000.00, 8500,
    2000, 'Gasoline', 1, 'B 320 BOS', '2025-10-10', 'Blue', 5, 8, 'Yes', 'Yes', 
    '2.0L BMW TwinPower Turbo', '8-speed Steptronic', 184, 'Available', 'Approved',
    '../../images/raize.png', 'Sedan sport legendaris yang mendefinisikan kenikmatan berkendara.', 'bmw-320i-m-sport-2024-10'
),
(
    8, 1, 'Omoda 5 RZ', 2024, 404900000.00, 11000,
    1500, 'Gasoline', 1, 'BK 1505 RZ', '2025-03-12', 'Gray', 5, 6, 'N/A', 'N/A', 
    '1.5L Turbo', '9-speed CVT', 145, 'Available', 'Pending', 
    '../../images/raize.png', 'Crossover futuristik dengan desain "Art in Motion".', 'chery-omoda-5-rz-2024-11'
);

INSERT INTO car_images (car_id, image_path, caption, display_order) VALUES
-- Galeri untuk Mobil ID 1 (Avanza - 7 Seater)
(1, '../../images/cars/fisik/Avanza-tampak-serong.png', 'Eksterior - Tampak Serong', 1),
(1, '../../images/cars/fisik/Avanza-tampak-samping.png', 'Eksterior - Tampak Samping', 2),
(1, '../../images/cars/fisik/Avanza-tampak-depan.png', 'Eksterior - Tampak Depan', 3),
(1, '../../images/cars/fisik/Avanza-tampak-belakang.png', 'Eksterior - Tampak Belakang', 4),
(1, '../../images/cars/fisik/Avanza-tampak-velg.png', 'Detail - Tampak Velg', 5),
(1, '../../images/cars/fisik/Avanza-ruang-mesin.png', 'Detail - Ruang Mesin', 6),
(1, '../../images/cars/fisik/Avanza-interior-1.png', 'Interior - Interior Depan', 7),
(1, '../../images/cars/fisik/Avanza-speedometer.png', 'Detail - Speedometer', 8),
(1, '../../images/cars/fisik/Avanza-interior-2.png', 'Interior - Interior Belakang', 9),

-- Galeri untuk Mobil ID 2 (Pajero Sport - 7 Seater)
(2, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (2, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (2, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (2, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (2, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (2, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (2, '../../images/raize.png', 'Detail - Roda Depan', 7), (2, '../../images/raize.png', 'Detail - Roda Belakang', 8), (2, '../../images/raize.png', 'Detail - Lampu Depan', 9), (2, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (2, '../../images/raize.png', 'Detail - Ruang Mesin', 11), (2, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (2, '../../images/raize.png', 'Interior - View Pengemudi', 13), (2, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (2, '../../images/raize.png', 'Detail - Panel Instrumen & KM', 15), (2, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (2, '../../images/raize.png', 'Interior - Kabin Baris Kedua', 17), (2, '../../images/raize.png', 'Interior - Kabin Baris Ketiga', 18), (2, '../../images/raize.png', 'Interior - Plafon & Lampu Kabin', 19), (2, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (2, '../../images/raize.png', 'Detail - Kontrol AC', 21), (2, '../../images/raize.png', 'Detail - Panel Pintu Pengemudi', 22), (2, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (2, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (2, '../../images/raize.png', 'Fitur - Kamera 360', 25), (2, '../../images/raize.png', 'Fitur - Sunroof', 26),

-- Galeri untuk Mobil ID 3 (Brio - 5 Seater)
(3, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (3, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (3, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (3, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (3, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (3, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (3, '../../images/raize.png', 'Detail - Roda Depan', 7), (3, '../../images/raize.png', 'Detail - Roda Belakang', 8), (3, '../../images/raize.png', 'Detail - Lampu Depan', 9), (3, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (3, '../../images/raize.png', 'Detail - Ruang Mesin', 11), (3, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (3, '../../images/raize.png', 'Interior - View Pengemudi', 13), (3, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (3, '../../images/raize.png', 'Detail - Panel Instrumen & KM', 15), (3, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (3, '../../images/raize.png', 'Interior - Kabin Belakang', 17), (3, '../../images/raize.png', 'Interior - View Samping', 18), (3, '../../images/raize.png', 'Interior - Plafon & Lampu Kabin', 19), (3, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (3, '../../images/raize.png', 'Detail - Kontrol AC', 21), (3, '../../images/raize.png', 'Detail - Panel Pintu Pengemudi', 22), (3, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (3, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (3, '../../images/raize.png', 'Fitur - Sensor Parkir', 25), (3, '../../images/raize.png', 'Fitur - Spion', 26),

-- Galeri untuk Mobil ID 4 (Ioniq 5 - 5 Seater)
(4, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (4, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (4, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (4, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (4, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (4, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (4, '../../images/raize.png', 'Detail - Roda Depan', 7), (4, '../../images/raize.png', 'Detail - Roda Belakang', 8), (4, '../../images/raize.png', 'Detail - Lampu Depan Parametrik', 9), (4, '../../images/raize.png', 'Detail - Lampu Belakang Parametrik', 10), (4, '../../images/raize.png', 'Detail - Port Pengisian Daya', 11), (4, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (4, '../../images/raize.png', 'Interior - View Pengemudi', 13), (4, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (4, '../../images/raize.png', 'Detail - Panel Instrumen Digital', 15), (4, '../../images/raize.png', 'Detail - Head Unit Layar Sentuh', 16), (4, '../../images/raize.png', 'Interior - Kabin Belakang', 17), (4, '../../images/raize.png', 'Interior - Lantai Rata', 18), (4, '../../images/raize.png', 'Interior - Plafon & Panoramic Roof', 19), (4, '../../images/raize.png', 'Detail - Konsol Tengah Geser', 20), (4, '../../images/raize.png', 'Detail - Kontrol AC Digital', 21), (4, '../../images/raize.png', 'Detail - Panel Pintu', 22), (4, '../../images/raize.png', 'Detail - Bagasi Depan (Frunk)', 23), (4, '../../images/raize.png', 'Detail - Bagasi Belakang', 24), (4, '../../images/raize.png', 'Fitur - Kamera 360', 25), (4, '../../images/raize.png', 'Fitur - Smart Cruise Control', 26),

-- Galeri untuk Mobil ID 5 (Ertiga - 7 Seater)
(5, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (5, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (5, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (5, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (5, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (5, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (5, '../../images/raize.png', 'Detail - Roda Depan', 7), (5, '../../images/raize.png', 'Detail - Roda Belakang', 8), (5, '../../images/raize.png', 'Detail - Lampu Depan', 9), (5, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (5, '../../images/raize.png', 'Detail - Ruang Mesin', 11), (5, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (5, '../../images/raize.png', 'Interior - View Pengemudi', 13), (5, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (5, '../../images/raize.png', 'Detail - Panel Instrumen & KM', 15), (5, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (5, '../../images/raize.png', 'Interior - Kabin Baris Kedua', 17), (5, '../../images/raize.png', 'Interior - Kabin Baris Ketiga', 18), (5, '../../images/raize.png', 'Interior - Plafon & AC Double Blower', 19), (5, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (5, '../../images/raize.png', 'Detail - Kontrol AC', 21), (5, '../../images/raize.png', 'Detail - Panel Pintu', 22), (5, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (5, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (5, '../../images/raize.png', 'Fitur - Kamera Mundur', 25), (5, '../../images/raize.png', 'Fitur - Spion Elektrik', 26),

-- Galeri untuk Mobil ID 6 (Rocky - 5 Seater)
(6, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (6, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (6, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (6, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (6, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (6, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (6, '../../images/raize.png', 'Detail - Roda Depan', 7), (6, '../../images/raize.png', 'Detail - Roda Belakang', 8), (6, '../../images/raize.png', 'Detail - Lampu Depan LED', 9), (6, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (6, '../../images/raize.png', 'Detail - Ruang Mesin Turbo', 11), (6, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (6, '../../images/raize.png', 'Interior - View Pengemudi', 13), (6, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (6, '../../images/raize.png', 'Detail - Panel Instrumen Digital', 15), (6, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (6, '../../images/raize.png', 'Interior - Kabin Belakang', 17), (6, '../../images/raize.png', 'Interior - View Samping', 18), (6, '../../images/raize.png', 'Interior - Plafon & Lampu Kabin', 19), (6, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (6, '../../images/raize.png', 'Detail - Kontrol AC Digital', 21), (6, '../../images/raize.png', 'Detail - Panel Pintu', 22), (6, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (6, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (6, '../../images/raize.png', 'Fitur - Kamera Mundur', 25), (6, '../../images/raize.png', 'Fitur - ASA Safety System', 26),

-- Galeri untuk Mobil ID 7 (Air EV - 4 Seater)
(7, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (7, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (7, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (7, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (7, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (7, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (7, '../../images/raize.png', 'Detail - Roda Depan', 7), (7, '../../images/raize.png', 'Detail - Roda Belakang', 8), (7, '../../images/raize.png', 'Detail - Lampu Depan', 9), (7, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (7, '../../images/raize.png', 'Detail - Port Pengisian Daya', 11), (7, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (7, '../../images/raize.png', 'Interior - View Pengemudi', 13), (7, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (7, '../../images/raize.png', 'Detail - Layar Instrumen Ganda', 15), (7, '../../images/raize.png', 'Detail - Head Unit Layar Sentuh', 16), (7, '../../images/raize.png', 'Interior - Kabin Belakang (2 Kursi)', 17), (7, '../../images/raize.png', 'Interior - View Samping', 18), (7, '../../images/raize.png', 'Interior - Plafon Kabin', 19), (7, '../../images/raize.png', 'Detail - Transmisi Putar', 20), (7, '../../images/raize.png', 'Detail - Kontrol AC', 21), (7, '../../images/raize.png', 'Detail - Panel Pintu', 22), (7, '../../images/raize.png', 'Detail - Bagasi (Kursi Dilipat)', 23), (7, '../../images/raize.png', 'Detail - Bagasi (Normal)', 24), (7, '../../images/raize.png', 'Fitur - Kamera Mundur', 25), (7, '../../images/raize.png', 'Fitur - Keyless Entry', 26),

-- Galeri untuk Mobil ID 8 (Innova Zenix - 7 Seater)
(8, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (8, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (8, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (8, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (8, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (8, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (8, '../../images/raize.png', 'Detail - Roda Depan', 7), (8, '../../images/raize.png', 'Detail - Roda Belakang', 8), (8, '../../images/raize.png', 'Detail - Lampu Depan LED', 9), (8, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (8, '../../images/raize.png', 'Detail - Ruang Mesin Hybrid', 11), (8, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (8, '../../images/raize.png', 'Interior - View Pengemudi', 13), (8, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (8, '../../images/raize.png', 'Detail - Panel Instrumen Digital', 15), (8, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (8, '../../images/raize.png', 'Interior - Kabin Baris Kedua', 17), (8, '../../images/raize.png', 'Interior - Kabin Baris Ketiga', 18), (8, '../../images/raize.png', 'Interior - Plafon & Panoramic Roof', 19), (8, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (8, '../../images/raize.png', 'Detail - Kontrol AC Digital', 21), (8, '../../images/raize.png', 'Detail - Panel Pintu', 22), (8, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (8, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (8, '../../images/raize.png', 'Fitur - Kamera 360', 25), (8, '../../images/raize.png', 'Fitur - Toyota Safety Sense', 26),

-- Galeri untuk Mobil ID 9 (HR-V - 5 Seater)
(9, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (9, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (9, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (9, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (9, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (9, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (9, '../../images/raize.png', 'Detail - Roda Depan', 7), (9, '../../images/raize.png', 'Detail - Roda Belakang', 8), (9, '../../images/raize.png', 'Detail - Lampu Depan', 9), (9, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (9, '../../images/raize.png', 'Detail - Ruang Mesin Turbo', 11), (9, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (9, '../../images/raize.png', 'Interior - View Pengemudi', 13), (9, '../../images/raize.png', 'Interior - View Penumpang Depan', 14), (9, '../../images/raize.png', 'Detail - Panel Instrumen Digital', 15), (9, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (9, '../../images/raize.png', 'Interior - Kabin Belakang', 17), (9, '../../images/raize.png', 'Interior - View Samping', 18), (9, '../../images/raize.png', 'Interior - Plafon & Panoramic Roof', 19), (9, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (9, '../../images/raize.png', 'Detail - Kontrol AC Digital', 21), (9, '../../images/raize.png', 'Detail - Panel Pintu', 22), (9, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (9, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (9, '../../images/raize.png', 'Fitur - Kamera Mundur', 25), (9, '../../images/raize.png', 'Fitur - Honda Sensing', 26),

-- Galeri untuk Mobil ID 10 (BMW 320i - 5 Seater)
(10, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (10, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (10, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (10, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (10, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (10, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (10, '../../images/raize.png', 'Detail - Roda M Sport', 7), (10, '../../images/raize.png', 'Detail - Roda Belakang', 8), (10, '../../images/raize.png', 'Detail - Lampu Laserlight', 9), (10, '../../images/raize.png', 'Detail - Knalpot Ganda', 10), (10, '../../images/raize.png', 'Detail - Ruang Mesin TwinPower', 11), (10, '../../images/raize.png', 'Interior - Dasbor & Kemudi M Sport', 12), (10, '../../images/raize.png', 'Interior - View Pengemudi', 13), (10, '../../images/raize.png', 'Interior - Jok Sport', 14), (10, '../../images/raize.png', 'Detail - BMW Live Cockpit', 15), (10, '../../images/raize.png', 'Detail - iDrive Controller', 16), (10, '../../images/raize.png', 'Interior - Kabin Belakang', 17), (10, '../../images/raize.png', 'Interior - Ambient Lighting', 18), (10, '../../images/raize.png', 'Interior - Plafon & Sunroof', 19), (10, '../../images/raize.png', 'Detail - Transmisi Steptronic', 20), (10, '../../images/raize.png', 'Detail - Kontrol AC 3-Zona', 21), (10, '../../images/raize.png', 'Detail - Panel Pintu', 22), (10, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (10, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (10, '../../images/raize.png', 'Fitur - Kamera 360', 25), (10, '../../images/raize.png', 'Fitur - Parking Assistant', 26),

-- Galeri untuk Mobil ID 11 (Omoda 5 - 5 Seater)
(11, '../../images/raize.png', 'Eksterior - Tampak Depan', 1), (11, '../../images/raize.png', 'Eksterior - Tampak Belakang', 2), (11, '../../images/raize.png', 'Eksterior - Sisi Kanan', 3), (11, '../../images/raize.png', 'Eksterior - Sisi Kiri', 4), (11, '../../images/raize.png', 'Eksterior - Depan Serong', 5), (11, '../../images/raize.png', 'Eksterior - Belakang Serong', 6), (11, '../../images/raize.png', 'Detail - Roda Depan', 7), (11, '../../images/raize.png', 'Detail - Roda Belakang', 8), (11, '../../images/raize.png', 'Detail - Lampu Depan LED', 9), (11, '../../images/raize.png', 'Detail - Lampu Belakang', 10), (11, '../../images/raize.png', 'Detail - Ruang Mesin Turbo', 11), (11, '../../images/raize.png', 'Interior - Dasbor & Kemudi', 12), (11, '../../images/raize.png', 'Interior - View Pengemudi', 13), (11, '../../images/raize.png', 'Interior - Jok Kulit', 14), (11, '../../images/raize.png', 'Detail - Panel Instrumen Digital', 15), (11, '../../images/raize.png', 'Detail - Head Unit Utama', 16), (11, '../../images/raize.png', 'Interior - Kabin Belakang', 17), (11, '../../images/raize.png', 'Interior - Ambient Lighting', 18), (11, '../../images/raize.png', 'Interior - Plafon & Sunroof', 19), (11, '../../images/raize.png', 'Detail - Konsol Tengah & Transmisi', 20), (11, '../../images/raize.png', 'Detail - Kontrol AC Digital', 21), (11, '../../images/raize.png', 'Detail - Panel Pintu', 22), (11, '../../images/raize.png', 'Detail - Bagasi (Tertutup)', 23), (11, '../../images/raize.png', 'Detail - Bagasi (Terbuka)', 24), (11, '../../images/raize.png', 'Fitur - Kamera 360', 25), (11, '../../images/raize.png', 'Fitur - ADAS', 26);


INSERT INTO car_inspection_images (car_id, image_path, caption, display_order) VALUES
-- Foto Inspeksi untuk Mobil ID 1 (Avanza)
(1, '../../images/cars/inspeksi/Avanza-depan-batu.png', 'Inspeksi - Goresan halus akibat batu pada bumper depan', 1),
(1, '../../images/cars/inspeksi/Avanza-baret-kiri-pintu.png', 'Inspeksi - Goresan halus di sisi kiri pintu', 2),
(1, '../../images/cars/inspeksi/Avanza-baret-velg.png', 'Inspeksi -Velg terkena lecetan sedikit', 3),


-- Foto Inspeksi untuk Mobil ID 2 (Pajero Sport)
(2, '../../images/raize.png', 'Inspeksi - Kondisi kaki-kaki tidak ada rembesan', 1),
(2, '../../images/raize.png', 'Inspeksi - Kualitas dan volume oli mesin', 2),
(2, '../../images/raize.png', 'Inspeksi - Sunroof terbuka dan tertutup normal', 3),
(2, '../../images/raize.png', 'Inspeksi - Semua sisi kamera 360 berfungsi', 4);


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





INSERT INTO users (user_id, name, no_hp, password, email, role, alamat)VALUES 
('USR001', 'Bryan Cen', '08123456789', '$2y$12$CJmgB3OqQXUFgC4nAoLS7OdV0V92HZCPFsJnfjudV/nlk9qKHzlF6', 'bryancenbryan@gmail.com', 'customer', 'Jl. Melati No. 1'),
('USR002', 'Randy', '08121234567', '$2y$12$g.TSz1lJE5loXaf6/dn1I.88n0RRONEF4CmtBfAiVIJyHudfA0mA.', 'randy@gmail.com', 'admin', 'Jl. Mawar No. 10');




INSERT INTO daftarBahanBakar (name) VALUES
('Gasoline'),
('Diesel'),
('Hybrid'),
('Electric');

INSERT INTO leasing_rules (leasing_name, min_dp_percentage, max_dp_percentage, admin_fee, interest_rate_1yr, interest_rate_2yr, interest_rate_3yr, interest_rate_4yr, interest_rate_5yr) VALUES
('BCA Finance', 20.00, 80.00, 500000.00, 3.88, 4.28, 4.58, 4.88, 5.28),
('Adira Finance', 15.00, 75.00, 750000.00, 4.10, 4.50, 4.80, 5.10, 5.50),
('Mandiri Tunas Finance', 25.00, 85.00, 600000.00, 3.95, 4.35, 4.65, 4.95, 5.35);

INSERT INTO staff_pemasaran (nama, jabatan, email, telepon, showroom_id) 
VALUES 
('Andi Wijaya', 'Sales Head', 'andi.w@email.com', '081234567890', 1),
('Citra Lestari', 'Sales Executive', 'citra.l@email.com', '081234567891', 1),
('Anton', 'Sales Head', 'anton.w@email.com', '08123456890', 1),
('Rann', 'Sales Executive', 'rann.l@email.com', '08134567891', 1);

INSERT INTO penjualan 
(transaction_code, car_id, customer_id, guest_customer_name, showroom_id, staff_id, sale_date, car_price_at_sale, admin_fee, discount, final_price, status, leasing_rule_id, dp_amount, loan_tenor_years, monthly_installment) 
VALUES
('TRX-001', 1, 'USR002', NULL, 1, 1, '2025-07-10', 272000000, 500000, 2000000, 270500000, 'lunas', NULL, NULL, NULL, NULL),

('TRX-002', 2, NULL, 'Ibu Citra Lestari (Tamu)', 2, 2, '2025-07-08', 675000000, 750000, 0, 675750000, 'dp', 2, 150000000, 5, 11562500);
