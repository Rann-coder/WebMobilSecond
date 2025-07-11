<?php

require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

header('Content-Type: application/json'); //utk kasi tau browser teks yang dikirim dlm bntk Json
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

try {
    //Ambil data
    $selectedBrands = json_decode($_POST['brands'] ?? '["All"]'); //'["Toyota"] --> ['Toyota']
    $selectedTypes = json_decode($_POST['types'] ?? '["All"]');
    $showroomId = $_POST['showroom_id'] ?? null;
    //Distinct --> pastikan ga ada data double
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

    if ($showroomId) {
        $sql .= " AND c.showroom_id = ?";
        $params[] = $showroomId;
    }

    if (!empty($selectedBrands) && !in_array('All', $selectedBrands)) {
        $placeholders = implode(',', array_fill(0, count($selectedBrands), '?')); 
        $sql .= " AND b.name IN ($placeholders)";
        $params = array_merge($params, $selectedBrands);
    }

    if (!empty($selectedTypes) && !in_array('All', $selectedTypes)) {
        $placeholders = implode(',', array_fill(0, count($selectedTypes), '?'));
        $sql .= " AND dt.name IN ($placeholders)";
        $params = array_merge($params, $selectedTypes);
        $typesToMatch = count($selectedTypes);
    }
    
    if ($typesToMatch > 1) {
        $sql .= " GROUP BY c.id HAVING COUNT(DISTINCT dt.id) = ?";
        $params[] = $typesToMatch;
    } else if ($typesToMatch == 1) {
        $sql .= " GROUP BY c.id";
    }
    
    $sql .= " ORDER BY c.name ASC";
    
    $db = DB::getDB();
    $q = $db->prepare($sql);
    $q->execute($params);
    $cars = $q->fetchAll(PDO::FETCH_ASSOC);

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

    echo json_encode([
        'success' => true,
        'data' => $cars,
        'count' => count($cars)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(), 
        'trace' => $e->getTraceAsString()
    ]);
}