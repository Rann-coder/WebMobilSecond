<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    die("Mobil tidak ditemukan");
}

try {
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

    if (!$car) {
        http_response_code(404);
        die("Maaf, mobil tidak ditemukan");
    }

    $carId = $car['id'];

    $sql_images = "SELECT image_path, caption FROM car_images WHERE car_id = ? ORDER BY display_order ASC"; 
    $stmt_images = $db->prepare($sql_images);
    $stmt_images->execute([$car['id']]);
    $galleryImages = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

    $sqlInspection = "SELECT image_path, caption FROM car_inspection_images WHERE car_id = :car_id ORDER BY display_order ASC";
    $qInspection = $db->prepare($sqlInspection);
    $qInspection->execute(['car_id' => $carId]);
    $inspectionImages = $qInspection->fetchAll(PDO::FETCH_ASSOC);

    $sql_leasing = "SELECT leasing_name FROM leasing_rules WHERE is_active = TRUE ORDER BY leasing_name ASC";
    $stmt_leasing = $db->query($sql_leasing);
    $leasingPartners = $stmt_leasing->fetchAll(PDO::FETCH_ASSOC);
    
    $alreadyLiked = false;
    $username = 'Calon Pembeli'; 
    $whatsapp_url_booking = 'login.php';
    $whatsapp_url_diskusi = '';
    $whatsapp_url_testdrive = 'login.php';
    
    $nomor_wa_tujuan = '6282168498081'; 

    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user']['name'];
        
        $userId = $_SESSION['user']['id']; 

        $stmtLiked = $db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND car_id = ?");
        $stmtLiked->execute([$userId, $car['id']]);
        $alreadyLiked = $stmtLiked->fetchColumn() > 0;
        
        $pesan_booking = "Halo MobilSecond.id,\n\nSaya $username, tertarik dan ingin memesan mobil berikut:\n\nNama Mobil: " . $car['name'] . "\nPlat Nomor: " . $car['license_plate'] . "\nHarga: Rp " . number_format($car['price']) . "\n\nMohon informasinya lebih lanjut. Terima kasih.";
        $whatsapp_url_booking = "https://wa.me/" . $nomor_wa_tujuan . "?text=" . urlencode($pesan_booking);

        $pesan_testdrive = "Halo MobilSecond.id,\n\nSaya $username, tertarik dan ingin melakukan test drive untuk mobil berikut:\n\nNama Mobil: " . $car['name'] . "\nPlat Nomor: " . $car['license_plate'] . "\n\nMohon informasinya mengenai jadwal dan lokasi. Terima kasih.";
        $whatsapp_url_testdrive = "https://wa.me/" . $nomor_wa_tujuan . "?text=" . urlencode($pesan_testdrive);
    }
    
    $pesan_diskusi = "Halo MobilSecond.id,\n\nSaya ingin menanyakan mobil berikut:\n\nNama Mobil: " . $car['name'] . "\nPlat Nomor: " . $car['license_plate'] . "\n\nMohon informasinya lebih lanjut. Terima kasih.";
    $whatsapp_url_diskusi = "https://wa.me/" . $nomor_wa_tujuan . "?text=" . urlencode($pesan_diskusi);
    

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
      'inspectionImages' => $inspectionImages,
      'leasingPartners' => $leasingPartners,
      'whatsapp_url_booking' => $whatsapp_url_booking,
      'whatsapp_url_diskusi' => $whatsapp_url_diskusi,
      'whatsapp_url_testdrive' => $whatsapp_url_testdrive,
      'session' => $_SESSION,
      'alreadyLiked' => $alreadyLiked,
      'username' => $username
    ]
);
