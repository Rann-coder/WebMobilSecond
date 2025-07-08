<?php

require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$slug = $_GET['slug']??null;

if(!$slug){
    die("Mobil tidak ditemukan");
}

try{
    $db = DB::getDB();
    $sql = "SELECT 
                c.*, 
                b.name AS brand_name 
            FROM cars AS c
            JOIN daftarBrands AS b ON c.id_brand = b.id
            WHERE c.slug = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$slug]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$car){
        http_response_code(404);
        die("Maaf, mobil tidak ditemukan");
    }

    $sql_images = "SELECT image_path, caption 
                   FROM car_images 
                   WHERE car_id = ? 
                   ORDER BY display_order ASC";
    $stmt_images = $db->prepare($sql_images);
    $stmt_images->execute([$car['id']]);
    $galleryImages = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

    $sql_leasing = "SELECT leasing_name FROM leasing_rules WHERE is_active = TRUE ORDER BY leasing_name ASC";
    $stmt_leasing = $db->query($sql_leasing);
    $leasingPartners = $stmt_leasing->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e){
    http_response_code(500);
    die("Terjadi kesalahan pada server: ". $e->getMessage());
}

$twig = Twig::make('../templates-user');
echo $twig->render(
    'details-car.twig.html',
    [
      'car'=> $car,
      'galleryImages' => $galleryImages,
      'leasingPartners' => $leasingPartners
    ]
);