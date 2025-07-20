<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

if (!isset($_SESSION['admin_user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin-login.php');
    exit();
}

$db = DB::getDB();

function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text); 
    $text = preg_replace('/[\s-]+/', '-', $text);     
    return $text . '-' . uniqid();                      
}

$db->beginTransaction();

try {
    $slug = slugify($_POST['name']);
    
    $sqlCar = "INSERT INTO cars (
        id_brand, showroom_id, name, price, year, color, status, km, fuel_type, 
        engine_cc, horsepower, transmission, engine, seat_count, airbag_count,
        previous_owners, license_plate, tax_valid_until, is_flood_free, 
        is_accident_free, description, slug, image_url
    ) VALUES (
        :id_brand, :showroom_id, :name, :price, :year, :color, :status, :km, 
        :fuel_type, :engine_cc, :horsepower, :transmission, :engine, :seat_count, 
        :airbag_count, :previous_owners, :license_plate, :tax_valid_until, 
        :is_flood_free, :is_accident_free, :description, :slug, :image_url
    )";
    
    $stmtCar = $db->prepare($sqlCar);
    $stmtCar->execute([
        'id_brand' => $_POST['id_brand'],
        'showroom_id' => $_POST['showroom_id'],
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'year' => $_POST['year'],
        'color' => $_POST['color'],
        'status' => $_POST['status'],
        'km' => $_POST['km'] ?: null,
        'fuel_type' => $_POST['fuel_type'],
        'engine_cc' => $_POST['engine_cc'] ?: null,
        'horsepower' => $_POST['horsepower'] ?: null,
        'transmission' => $_POST['transmission'],
        'engine' => $_POST['engine'],
        'seat_count' => $_POST['seat_count'] ?: null, 
        'airbag_count' => $_POST['airbag_count'] ?: null,
        'previous_owners' => $_POST['previous_owners'],
        'license_plate' => $_POST['license_plate'],
        'tax_valid_until' => !empty($_POST['tax_valid_until']) ? $_POST['tax_valid_until'] : null,
        'is_flood_free' => $_POST['is_flood_free'],
        'is_accident_free' => $_POST['is_accident_free'],
        'description' => $_POST['description'] ?: null,
        'slug' => $slug,
        'image_url' => null
    ]);
    
    $carId = $db->lastInsertId();
    $mainImagePath = null;

    if (!empty($_FILES['gallery_images']['name'][0])) {
        $galleryImages = $_FILES['gallery_images'];
        $captions = $_POST['captions'];
        $uploadDir = '../images/cars/';
        if (!file_exists($uploadDir)) { mkdir($uploadDir, 0775, true); }

        foreach ($galleryImages['name'] as $index => $name) {
            if (!empty($name) && $galleryImages['error'][$index] === UPLOAD_ERR_OK) {
                $tmpName = $galleryImages['tmp_name'][$index];
                $newFileName = 'car-' . $carId . '-' . uniqid() . '-' . basename($name);
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = 'images/cars/' . $newFileName;
                    if ($index === 0) {
                        $mainImagePath = $imagePath;
                    }
                    $sqlImage = "INSERT INTO car_images (car_id, image_path, caption, display_order) VALUES (?, ?, ?, ?)";
                    $stmtImage = $db->prepare($sqlImage);
                    $stmtImage->execute([$carId, $imagePath, $captions[$index] ?? '', $index + 1]);
                }
            }
        }
    }

    if ($mainImagePath) {
        $stmtUpdate = $db->prepare("UPDATE cars SET image_url = ? WHERE id = ?");
        $stmtUpdate->execute([$mainImagePath, $carId]);
    }

    if (!empty($_FILES['inspection_images']['name'][0])) {
        $inspectionImages = $_FILES['inspection_images'];
        $inspectionCaptions = $_POST['inspection_captions'];
        $uploadDir = '../images/cars/inspeksi/';
        if (!file_exists($uploadDir)) { mkdir($uploadDir, 0775, true); }

        foreach ($inspectionImages['name'] as $index => $name) {
            if (!empty($name) && $inspectionImages['error'][$index] === UPLOAD_ERR_OK) {
                $tmpName = $inspectionImages['tmp_name'][$index];
                $newFileName = 'inspeksi-' . $carId . '-' . uniqid() . '-' . basename($name);
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = 'images/cars/inspeksi/' . $newFileName;
                    $sqlInspection = "INSERT INTO car_inspection_images (car_id, image_path, caption, display_order) VALUES (?, ?, ?, ?)";
                    $stmtInspection = $db->prepare($sqlInspection);
                    $stmtInspection->execute([$carId, $imagePath, $inspectionCaptions[$index] ?? '', $index + 1]);
                }
            }
        }
    }

    if (!empty($_POST['car_types']) && is_array($_POST['car_types'])) {
        $sqlCarType = "INSERT INTO car_types (car_id, type_id) VALUES (?, ?)";
        $stmtCarType = $db->prepare($sqlCarType);
        
        foreach ($_POST['car_types'] as $typeId) {
            $stmtCarType->execute([$carId, (int)$typeId]);
        }
    }

    $db->commit();

    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Mobil baru berhasil ditambahkan!'];
    session_write_close();
    header('Location: admin-manage-cars.php');
    exit;

} catch (Exception $e) {
   /* $db->rollBack();
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Gagal menyimpan data mobil: ' . $e->getMessage()];
    session_write_close();
    header('Location: admin-add-car.php');
    exit();*/

    die("<h1>Terjadi Error!</h1><p>Gagal menyimpan data mobil. Berikut adalah pesan error dari database:</p><pre>" . $e->getMessage() . "</pre>");
}