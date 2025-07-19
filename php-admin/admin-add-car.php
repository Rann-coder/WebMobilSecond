<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    header('Location: admin-login.php');
    exit();
}

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();
$twig = Twig::make('../templates-admin');

try {
    $brandStmt = $db->query("SELECT id, name FROM daftarBrands ORDER BY name");
    $brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);

    $fuelStmt = $db->query("SELECT name FROM daftarBahanBakar ORDER BY name");
    $fuels = $fuelStmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtTypes = $db->query("SELECT id, name FROM daftarTypes ORDER BY name ASC");
    $car_types_list = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

    $stmtShowrooms = $db->query("SELECT id, name FROM showrooms WHERE status = 'active' ORDER BY name ASC");
    $showrooms = $stmtShowrooms->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('admin-add-car.twig.html', [
        'brands' => $brands,
        'fuels' => $fuels,
        'car_types_list' => $car_types_list,
        'showrooms' => $showrooms 
    ]);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}