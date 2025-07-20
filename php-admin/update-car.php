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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin-manage-cars.php');
    exit();
}

$db = DB::getDB();
$db->beginTransaction(); 

try {
    $carId = $_POST['car_id'];

    $sql_update_car = "UPDATE cars SET 
        name = :name, id_brand = :id_brand, price = :price, year = :year, color = :color, status = :status,
        km = :km, fuel_type = :fuel_type, engine_cc = :engine_cc, horsepower = :horsepower, 
        transmission = :transmission, engine = :engine, previous_owners = :previous_owners, 
        license_plate = :license_plate, tax_valid_until = :tax_valid_until, 
        is_flood_free = :is_flood_free, is_accident_free = :is_accident_free, description = :description
        WHERE id = :car_id";

    $stmt_update_car = $db->prepare($sql_update_car);
    $stmt_update_car->execute([
        ':name' => $_POST['name'],
        ':id_brand' => $_POST['id_brand'],
        ':price' => $_POST['price'],
        ':year' => $_POST['year'],
        ':color' => $_POST['color'],
        ':status' => $_POST['status'],
        ':km' => $_POST['km'] ?: null,
        ':fuel_type' => $_POST['fuel_type'],
        ':engine_cc' => $_POST['engine_cc'] ?: null,
        ':horsepower' => $_POST['horsepower'] ?: null,
        ':transmission' => $_POST['transmission'],
        ':engine' => $_POST['engine'],
        ':previous_owners' => $_POST['previous_owners'],
        ':license_plate' => $_POST['license_plate'],
        ':tax_valid_until' => !empty($_POST['tax_valid_until']) ? $_POST['tax_valid_until'] : null,
        ':is_flood_free' => $_POST['is_flood_free'],
        ':is_accident_free' => $_POST['is_accident_free'],
        ':description' => $_POST['description'],
        ':car_id' => $carId
    ]);

    function deleteImages($db, $imageIds, $tableName) {
        if (empty($imageIds)) return;
        $ids = explode(',', $imageIds);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $db->prepare("SELECT image_path FROM $tableName WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $paths = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($paths as $path) {
            if (file_exists('../' . $path)) {
                unlink('../' . $path);
            }
        }
        $stmt_delete = $db->prepare("DELETE FROM $tableName WHERE id IN ($placeholders)");
        $stmt_delete->execute($ids);
    }
    deleteImages($db, $_POST['deleted_images'] ?? '', 'car_images');
    deleteImages($db, $_POST['deleted_inspections'] ?? '', 'car_inspection_images');

    function updateCaptions($db, $existingIds, $captions, $tableName) {
        if (empty($existingIds)) return;
        $stmt = $db->prepare("UPDATE $tableName SET caption = ? WHERE id = ?");
        foreach ($existingIds as $index => $id) {
            $stmt->execute([$captions[$index] ?? '', $id]);
        }
    }
    updateCaptions($db, $_POST['existing_images'] ?? [], $_POST['captions'] ?? [], 'car_images');
    updateCaptions($db, $_POST['existing_inspections'] ?? [], $_POST['inspection_captions'] ?? [], 'car_inspection_images');
    function addNewImages($db, $carId, $files, $captions, $tableName, $subfolder = '') {
        if (empty($files['name'][0])) return;
        
        $uploadDir = '../images/cars/' . $subfolder;
        if (!file_exists($uploadDir)) { mkdir($uploadDir, 0775, true); }

        $sql = "INSERT INTO $tableName (car_id, image_path, caption, display_order) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        foreach ($files['name'] as $index => $name) {
            if ($files['error'][$index] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$index];
                $newFileName = 'car-' . $carId . '-' . uniqid() . '-' . basename($name);
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = 'images/cars/' . $subfolder . $newFileName;
                    $caption = $captions[$index] ?? '';
                    $stmt->execute([$carId, $imagePath, $caption, 99]); 
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
