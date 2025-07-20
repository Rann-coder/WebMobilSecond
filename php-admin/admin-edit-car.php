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

$id = $_GET['id'] ?? null;
$car = null;
$galleryImages = [];
$inspectionImages = [];
$brands = [];
$fuels = [];
$showrooms = []; 

if ($id) {
    try {
        $db = DB::getDB();
        $stmt = $db->prepare("
            SELECT c.*, b.name AS brand_name 
            FROM cars c
            JOIN daftarBrands b ON c.id_brand = b.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) {
            $galleryStmt = $db->prepare("
                SELECT id, image_path, caption 
                FROM car_images 
                WHERE car_id = ? 
                ORDER BY display_order ASC
            ");
            $galleryStmt->execute([$id]);
            $galleryImages = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $inspectionStmt = $db->prepare("
                SELECT id, image_path, caption 
                FROM car_inspection_images 
                WHERE car_id = ? 
                ORDER BY display_order ASC
            ");
            $inspectionStmt->execute([$id]);
            $inspectionImages = $inspectionStmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

$brandStmt = $db->query("SELECT id, name FROM daftarBrands ORDER BY name");
$brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);

$fuelStmt = $db->query("SELECT name FROM daftarBahanBakar ORDER BY name");
$fuels = $fuelStmt->fetchAll(PDO::FETCH_ASSOC);

$showroomStmt = $db->query("SELECT id, name FROM showrooms WHERE status = 'active' ORDER BY name ASC");
$showrooms = $showroomStmt->fetchAll(PDO::FETCH_ASSOC);

$twig = Twig::make('../templates-admin');
echo $twig->render('admin-edit-car.twig.html', [
    'car' => $car,
    'gallery' => $galleryImages,
    'inspectionImages' => $inspectionImages,
    'brands' => $brands,
    'fuels' => $fuels,
    'showrooms' => $showrooms
]);