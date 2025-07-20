<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

if (!isset($_SESSION['admin_user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin-login.php');
    exit();
}

$db = DB::getDB();
$db->beginTransaction();

try {
    $carId = $_POST['car_id'];

    $sql_update_car = "UPDATE cars SET 
        name = :name, id_brand = :id_brand, showroom_id = :showroom_id, price = :price, 
        year = :year, color = :color, status = :status, km = :km, fuel_type = :fuel_type, 
        engine_cc = :engine_cc, horsepower = :horsepower, transmission = :transmission, 
        engine = :engine, seat_count = :seat_count, airbag_count = :airbag_count, 
        previous_owners = :previous_owners, license_plate = :license_plate, 
        tax_valid_until = :tax_valid_until, is_flood_free = :is_flood_free, 
        is_accident_free = :is_accident_free, description = :description,
        updated_at = NOW()
        WHERE id = :car_id";

    $stmt_update = $db->prepare($sql_update_car);
    $stmt_update->execute([
        ':name' => $_POST['name'],
        ':id_brand' => $_POST['id_brand'],
        ':showroom_id' => $_POST['showroom_id'], 
        ':price' => $_POST['price'],
        ':year' => $_POST['year'],
        ':color' => $_POST['color'] ?? null,
        ':status' => $_POST['status'],
        ':km' => $_POST['km'] ?? null,
        ':fuel_type' => $_POST['fuel_type'],
        ':engine_cc' => $_POST['engine_cc'] ?? null,
        ':horsepower' => $_POST['horsepower'] ?? null,
        ':transmission' => $_POST['transmission'] ?? null,
        ':engine' => $_POST['engine'] ?? null,
        ':seat_count' => $_POST['seat_count'] ?? null,
        ':airbag_count' => $_POST['airbag_count'] ?? null, 
        ':previous_owners' => $_POST['previous_owners'] ?? null,
        ':license_plate' => $_POST['license_plate'] ?? null,
        ':tax_valid_until' => $_POST['tax_valid_until'] ?? null,
        ':is_flood_free' => $_POST['is_flood_free'] ?? 'N/A',
        ':is_accident_free' => $_POST['is_accident_free'] ?? 'N/A',
        ':description' => $_POST['description'] ?? null,
        ':car_id' => $carId
    ]);

    function deleteImages($db, $imageIdsCSV, $tableName) {
        if (empty($imageIdsCSV)) return;
        $ids = explode(',', $imageIdsCSV);
        $ids = array_filter($ids, function($id) { return !empty(trim($id)); });
        if (empty($ids)) return;
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $db->prepare("SELECT image_path FROM $tableName WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $paths = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($paths as $path) {
            if ($path && file_exists('../' . $path)) {
                unlink('../' . $path);
            }
        }
        
        $stmt_delete = $db->prepare("DELETE FROM $tableName WHERE id IN ($placeholders)");
        $stmt_delete->execute($ids);
    }

    deleteImages($db, $_POST['deleted_images'] ?? '', 'car_images');
    deleteImages($db, $_POST['deleted_inspections'] ?? '', 'car_inspection_images');

    function updateCaptions($db, $captions, $tableName) {
        if (empty($captions) || !is_array($captions)) return;
        
        $stmt = $db->prepare("UPDATE $tableName SET caption = ? WHERE id = ?");
        foreach ($captions as $id => $caption) {
            if (!empty($id)) {
                $stmt->execute([$caption, $id]);
            }
        }
    }

    updateCaptions($db, $_POST['captions'] ?? [], 'car_images');
    
    if (!empty($_POST['inspection_captions']) && !empty($_POST['existing_inspections'])) {
        $existingInspections = $_POST['existing_inspections'];
        $inspectionCaptions = $_POST['inspection_captions'];
        
        $stmt = $db->prepare("UPDATE car_inspection_images SET caption = ? WHERE id = ?");
        foreach ($existingInspections as $index => $inspectionId) {
            if (!empty($inspectionId) && isset($inspectionCaptions[$index])) {
                $stmt->execute([$inspectionCaptions[$index], $inspectionId]);
            }
        }
    }

    function addNewImages($db, $carId, $files, $captions, $tableName, $subfolder = '') {
        if (empty($files) || empty($files['name']) || empty($files['name'][0])) return;
        
        $uploadDir = '../images/cars/';
        if (!empty($subfolder)) {
            $uploadDir .= $subfolder;
            if (!file_exists($uploadDir)) { 
                mkdir($uploadDir, 0775, true); 
            }
        }

        $sql = "INSERT INTO $tableName (car_id, image_path, caption) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);

        foreach ($files['name'] as $index => $name) {
            if ($files['error'][$index] === UPLOAD_ERR_OK && !empty($name)) {
                $tmpName = $files['tmp_name'][$index];
                
                $fileExtension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (!in_array($fileExtension, $allowedExtensions)) {
                    continue; 
                }
                
                $newFileName = 'car-' . $carId . '-' . uniqid() . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = 'images/cars/' . $subfolder . $newFileName;
                    $caption = isset($captions[$index]) ? $captions[$index] : '';
                    $stmt->execute([$carId, $imagePath, $caption]);
                }
            }
        }
    }

    addNewImages($db, $carId, $_FILES['new_gallery_images'] ?? [], $_POST['new_captions'] ?? [], 'car_images');
    
    addNewImages($db, $carId, $_FILES['new_inspection_images'] ?? [], $_POST['new_inspection_captions'] ?? [], 'car_inspection_images', 'inspeksi/');

    $db->commit();
    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data mobil berhasil diperbarui!'];
    header('Location: admin-manage-cars.php');
    exit;

} catch (Exception $e) {
    $db->rollBack();
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()];
    header('Location: admin-edit-car.php?id=' . ($_POST['car_id'] ?? 0));
    exit();
}
?>