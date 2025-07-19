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

$db = DB::getDB();
$twig = Twig::make('../templates-admin');

try {
    $carsStmt = $db->query("
    SELECT id, name, price, year, license_plate 
    FROM cars 
    WHERE status = 'Available' 
    ORDER BY name ASC
");
    $availableCars = $carsStmt->fetchAll(PDO::FETCH_ASSOC);

    $usersStmt = $db->query("
        SELECT user_id, name, email 
        FROM users 
        ORDER BY name ASC
    ");
    $customers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $showroomsStmt = $db->query("
        SELECT id, name 
        FROM showrooms 
        WHERE status = 'active' 
        ORDER BY name ASC
    ");
    $showrooms = $showroomsStmt->fetchAll(PDO::FETCH_ASSOC);
    $staffStmt = $db->query("
        SELECT id, nama 
        FROM staff_pemasaran 
        WHERE status = 'active' 
        ORDER BY nama ASC
    ");
    $staffs = $staffStmt->fetchAll(PDO::FETCH_ASSOC);

    $leasingStmt = $db->query("
        SELECT * FROM leasing_rules WHERE is_active = TRUE
    ");
    $leasingRules = $leasingStmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('admin-add-sale.twig.html', [
        'cars'      => $availableCars,
        'customers' => $customers,
        'showrooms' => $showrooms,
        'staffs'    => $staffs,
        'leasing_rules' => $leasingRules
    ]);

    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}