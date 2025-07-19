<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => 'Anda harus login sebagai admin untuk mengakses halaman ini.'
    ];
    header('Location: admin-login.php');
    exit(); 
}
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

try {
    $db = DB::getDB();
    
    
    $sql = "SELECT 
                id, 
                leasing_name, 
                min_dp_percentage, 
                max_dp_percentage,
                is_active
            FROM 
                leasing_rules 
            ORDER BY 
                leasing_name ASC";
            
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