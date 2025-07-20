<?php

require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$id_showroom = $_GET['id']??null;

if(!$id_showroom){
    die("Showroom tidak ditemukan");
}

try{
    $db = DB::getDB();
$sqlShowroom = "SELECT id, name, address, phone, opening_hours, status, image_url
                    FROM showrooms 
                    WHERE id = ?";
    
    $qShowroom = $db->prepare($sqlShowroom);
    $qShowroom->execute([$id_showroom]);
    $showroom = $qShowroom->fetch(PDO::FETCH_ASSOC);
    if (!$showroom) {
        die("Showroom tidak ditemukan.");
    }


    $sqlBrands = "SELECT id, name FROM daftarBrands ORDER BY name ASC";
    $qBrands = $db->prepare($sqlBrands);
    $qBrands->execute();
    $listBrands = $qBrands->fetchAll(PDO::FETCH_ASSOC);

    $sqlTypes = "SELECT id, name FROM daftarTypes ORDER BY name ASC";
    $qTypes = $db->prepare($sqlTypes);
    $qTypes->execute();
    $listTypes = $qTypes->fetchAll(PDO::FETCH_ASSOC);
    
    $sqlStaff = "SELECT nama, jabatan, telepon, email, foto_url 
                 FROM staff_pemasaran 
                 WHERE showroom_id = ?";
    $qStaff = $db->prepare($sqlStaff);
    $qStaff->execute([$id_showroom]);
    $staff_list = $qStaff->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e){
    http_response_code(500);
    die("Terjadi kesalahan pada server: ". $e->getMessage());
}

$twig = Twig::make('../templates-user');
echo $twig->render(
    'showroom.twig.html',
    [
      'showroom'=> $showroom,
      'brands'=> $listBrands,
      'types'=> $listTypes,
      'staff_list' => $staff_list
    ]
);