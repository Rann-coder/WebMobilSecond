<?php

require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $selectedBrands = json_decode($_POST['brands'] ?? '["All"]');
    $selectedTypes = json_decode($_POST['types'] ?? '["All"]');
    $searchTerm = trim($_POST['search_term'] ?? '');

    $priceMin = !empty($_POST['priceMin']) ? (int)$_POST['priceMin'] : null;
    $priceMax = !empty($_POST['priceMax']) ? (int)$_POST['priceMax'] : null;

    $yearMin = !empty($_POST['yearMin']) ? (int)$_POST['yearMin'] : null;
    $yearMax = !empty($_POST['yearMax']) ? (int)$_POST['yearMax'] : null;

    $kmMin = !empty($_POST['kmMin']) ? (int)$_POST['kmMin'] : null;
    $kmMax = !empty($_POST['kmMax']) ? (int)$_POST['kmMax'] : null;

    $transmission = trim($_POST['transmission'] ?? '');
    $owners = trim($_POST['owners'] ?? '');
    $engine = trim($_POST['engine'] ?? '');
    $fuelTypes = json_decode($_POST['fuelType'] ?? '[]');

    $sql = "SELECT DISTINCT 
                c.id, c.name, c.year, c.price, c.km, c.fuel_type, c.engine_cc, c.previous_owners, c.specifications, c.image_url, c.slug, b.name AS brand_name 
            FROM 
                cars AS c
            JOIN 
                daftarBrands AS b ON c.id_brand = b.id
            LEFT JOIN 
                car_types AS ct ON c.id = ct.car_id
            LEFT JOIN 
                daftarTypes AS dt ON ct.type_id = dt.id
            WHERE c.approval_status = 'Approved'"; 

    $params = [];
    $typesToMatch = 0;

    if (!empty($searchTerm)) {
        // Mencari di nama mobil ATAU nama merek
        $sql .= " AND (c.name LIKE ? OR b.name LIKE ?)";
        $params[] = '%' . $searchTerm . '%';
        $params[] = '%' . $searchTerm . '%';
    }

    // kondisi filter untuk Brands
    if (!empty($selectedBrands) && !in_array('All', $selectedBrands)) {
        $placeholders = implode(',', array_fill(0, count($selectedBrands), '?')); // toyota dan honda, jadi ['?', '?'] lalu implote jadi str "?,?"
        $sql .= " AND b.name IN ($placeholders)";
        $params = array_merge($params, $selectedBrands);
    }

    // kondisi filter untuk Types
    if (!empty($selectedTypes) && !in_array('All', $selectedTypes)) {
        $placeholders = implode(',', array_fill(0, count($selectedTypes), '?'));
        $sql .= " AND dt.name IN ($placeholders)";
        $params = array_merge($params, $selectedTypes);
        $typesToMatch = count($selectedTypes);
    }

    if ($priceMin !== null) { $sql .= " AND c.price >= ?"; $params[] = $priceMin; }
    if ($priceMax !== null) { $sql .= " AND c.price <= ?"; $params[] = $priceMax; }
    if ($yearMin !== null) { $sql .= " AND c.year >= ?"; $params[] = $yearMin; }
    if ($yearMax !== null) { $sql .= " AND c.year <= ?"; $params[] = $yearMax; }

    if ($kmMin !== null) { $sql .= " AND c.km >= ?"; $params[] = $kmMin; }
    if ($kmMax !== null) { $sql .= " AND c.km <= ?"; $params[] = $kmMax; }
    
    if (!empty($transmission)) {
        $sql .= " AND c.transmission = ?";
        $params[] = $transmission;
    }

    if (!empty($owners)) {
        if ($owners === '4+') {
            $sql .= " AND c.previous_owners >= ?";
            $params[] = 4;
        } else {
            $sql .= " AND c.previous_owners = ?";
            $params[] = (int)$owners;
        }
    }

    if (!empty($engine)) {
        if ($engine === '3000+') {
            $sql .= " AND c.engine_cc >= ?";
            $params[] = 3000;
        } else {
            list($engineMin, $engineMax) = explode('-', $engine);
            $sql .= " AND c.engine_cc BETWEEN ? AND ?";
            $params[] = (int)$engineMin;
            $params[] = (int)$engineMax;
        }
    }

    if (!empty($fuelTypes)) {
        $placeholders = implode(',', array_fill(0, count($fuelTypes), '?'));
        $sql .= " AND c.fuel_type IN ($placeholders)";
        $params = array_merge($params, $fuelTypes);
    }

    // Grouping jika ada multiple type dipilih
    if ($typesToMatch > 1) {
        $sql .= " GROUP BY c.id HAVING COUNT(DISTINCT dt.id) = ?";
        $params[] = $typesToMatch;
    } else if ($typesToMatch == 1) {
        $sql .= " GROUP BY c.id";
    }
    
    $sql .= " ORDER BY c.name ASC";

    //Eksekusi Query
    $db = DB::getDB();
    $q = $db->prepare($sql);
    $q->execute($params);
    $cars = $q->fetchAll(PDO::FETCH_ASSOC);

    // Format harga
    foreach ($cars as &$car) {
        if ($car['price']) {
            $car['formatted_price'] = 'Rp ' . number_format($car['price'], 0, ',', '.');
        } else {
            $car['formatted_price'] = 'Price on request';
        }

        if (isset($car['km'])) {
        $car['formatted_km'] = number_format($car['km'], 0, ',', '.') . ' km';
        } else {
            $car['formatted_km'] = 'N/A';
        }
    }
    
    // kirim hasil kembali sebagai JSON
    echo json_encode([
        'success' => true,
        'data' => $cars,
        'count' => count($cars)
    ]);

} catch (Exception $e) {
    // Jika ada error, kirim pesan error yang jelas
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(), 
        'trace' => $e->getTraceAsString()
    ]);
}