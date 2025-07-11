<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

try {
    $db = DB::getDB();
    
    
    $sql = "SELECT 
                lr.id, 
                lr.leasing_name, 
                lr.min_dp_percentage, 
                lr.max_dp_percentage
            FROM 
                leasing_rules AS lr
            GROUP BY 
                lr.id
            ORDER BY 
                lr.leasing_name ASC";
            
    $q = $db->prepare($sql);
    $q->execute();
    $leasings = $q->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    
    die("Terjadi kesalahan pada server: " . $e->getMessage());
}

$twig = Twig::make('../templates-admin'); 
echo $twig->render(
    'admin-leasing.twig.html',
    [
        'page_title' => 'Manajemen Mitra Leasing',
        'leasings' => $leasings
    ]
);