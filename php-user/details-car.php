<?php
session_start();
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
            b.name AS brand_name, 
            s.name AS showroom_name, 
            s.address AS showroom_address, 
            s.phone AS showroom_phone,
            GROUP_CONCAT(t.name SEPARATOR ', ') AS car_types
        FROM cars AS c 
        JOIN daftarBrands AS b ON c.id_brand = b.id 
        LEFT JOIN showrooms AS s ON c.showroom_id = s.id
        LEFT JOIN car_types AS ct ON c.id = ct.car_id
        LEFT JOIN daftarTypes AS t ON ct.type_id = t.id
        WHERE c.slug = ?
        GROUP BY c.id"; 
    
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

    $alreadyLiked = false;
    if (isset($_SESSION['user'])) {
        $stmtLiked = $db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND car_id = ?");
        $stmtLiked->execute([$_SESSION['user']['id'], $car['id']]);
        $alreadyLiked = $stmtLiked->fetchColumn() > 0;
    }



} catch (Exception $e){
    http_response_code(500);
    die("Terjadi kesalahan pada server: ". $e->getMessage());
}




$nomor_wa_tujuan = '6282168498081'; 

$pesan_raw = "Halo MobilSecond.id,\n\n" .
             "Saya tertarik dan ingin memesan mobil berikut:\n\n" .
             "Nama Mobil: " . $car['name'] . "\n" .
             "Plat Nomor: " . $car['license_plate'] . "\n" .
             "Tahun: " . $car['year'] . "\n" .
             "Kilometer: " . number_format($car['km']) . " km\n" .
             "Harga: Rp " . number_format($car['price']) . "\n\n" .
             "Mohon informasinya lebih lanjut. Terima kasih.";


$pesan_encoded = urlencode($pesan_raw);


$whatsapp_url = "https://wa.me/" . $nomor_wa_tujuan . "?text=" . $pesan_encoded;

$twig = Twig::make('../templates-user');
echo $twig->render(
    'details-car.twig.html',
    [
      'car'=> $car,
      'galleryImages' => $galleryImages,
      'leasingPartners' => $leasingPartners,
      'whatsapp_url' => $whatsapp_url,
      'session' => $_SESSION, // <-- penting agar bisa diakses di Twig
      'alreadyLiked' => $alreadyLiked
    ]
);