<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

try {
    $db = DB::getDB();
    //ambil mobil yang statusnya pending atau reviewed
    $sql = "SELECT id, name, price, created_at, slug, approval_status 
            FROM cars 
            WHERE approval_status IN ('Pending', 'Reviewed') 
            ORDER BY created_at DESC";
    
    $stmt = $db->query($sql);
    $pendingCars = $stmt->fetchAll();

} catch (Exception $e) {
    $pendingCars = []; 
}
$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-home.twig.html',
    [
        'pendingCars' => $pendingCars,
        'active_menu' => 'dashboard'
    ]
);