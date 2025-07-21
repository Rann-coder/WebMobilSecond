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
$gallery = [];
$inspectionImages = [];
if ($id) {
    try {
        $db = DB::getDB();
        $stmt = $db->prepare("
            SELECT c.*, b.name AS brand_name, sr.name as showroom_name
            FROM cars c
            JOIN daftarBrands b ON c.id_brand = b.id
            LEFT JOIN showrooms sr ON c.showroom_id = sr.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) {
            if ($car['approval_status'] === 'Pending') {
                $update = $db->prepare("UPDATE cars SET approval_status = 'Reviewed' WHERE id = ?");
                $update->execute([$id]);
                $car['approval_status'] = 'Reviewed';
            }

            $galleryStmt = $db->prepare("
                SELECT image_path, caption 
                FROM car_images 
                WHERE car_id = ? 
                ORDER BY display_order ASC
            ");
            $galleryStmt->execute([$id]);
            $galleryImages = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);
            $car['gallery'] = $galleryImages;
            
            $inspectionStmt = $db->prepare("
                SELECT image_path, caption 
                FROM car_inspection_images 
                WHERE car_id = ? 
                ORDER BY display_order ASC
            ");
            $inspectionStmt->execute([$id]);
            $inspectionImages = $inspectionStmt->fetchAll(PDO::FETCH_ASSOC);

            $typesStmt = $db->prepare("
                SELECT dt.name
                FROM car_types ct
                JOIN daftarTypes dt ON ct.type_id = dt.id
                WHERE ct.car_id = ?
                ORDER BY dt.name ASC
            ");
            $typesStmt->execute([$id]);
            $carTypes = $typesStmt->fetchAll(PDO::FETCH_ASSOC);
            $car['car_types'] = $carTypes;
        }

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

$twig = Twig::make('../templates-admin');
echo $twig->render('review-car.twig.html', [
    'car' => $car,
    'inspectionImages' => $inspectionImages 
]);