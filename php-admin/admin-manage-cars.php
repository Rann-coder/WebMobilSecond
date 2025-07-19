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
    $showroomStmt = $db->query("SELECT id, name FROM showrooms ORDER BY name ASC");
    $showrooms = $showroomStmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlCars = "
        SELECT 
            c.id, c.name, c.year, c.price, c.km, c.engine_cc, c.transmission, c.image_url, c.status,
            b.name AS brand_name,
            sr.name AS showroom_name
        FROM cars c
        LEFT JOIN daftarBrands b ON c.id_brand = b.id
        LEFT JOIN showrooms sr ON c.showroom_id = sr.id
        WHERE c.status IN ('Available', 'Hold')
        AND c.approval_status = 'Approved'
    ";
    
    $params = [];
    $selectedShowroom = $_GET['showroom_id'] ?? '';
    if (!empty($selectedShowroom) && $selectedShowroom !== 'all') {
        $sqlCars .= " AND c.showroom_id = :showroom_id"; 
        $params['showroom_id'] = $selectedShowroom;
    }
    
    $sqlCars .= " ORDER BY c.created_at DESC";

    $carsStmt = $db->prepare($sqlCars);
    $carsStmt->execute($params);
    $cars = $carsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('admin-manage-cars.twig.html', [
        'cars' => $cars,
        'showrooms' => $showrooms,
        'selected_showroom' => $selectedShowroom
    ]);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}