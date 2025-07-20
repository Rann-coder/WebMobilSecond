<?php

require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

header('Content-Type: application/json');

try {
    $db = DB::getDB();
    $sql = "SELECT * FROM leasing_rules WHERE is_active = TRUE";
    $stmt = $db->query($sql);
    $rules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo json_encode([
        'success' => true,
        'data' => $rules,
    ]);
} catch (Exception $e) {
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}