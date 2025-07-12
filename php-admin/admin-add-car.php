<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();

function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text); 
    $text = preg_replace('/[\s-]+/', '-', $text);     
    return $text . '-' . uniqid();                      
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $slug = slugify($_POST['name']);
    
    $mainImagePath = null;
    $uploadedImages = [];
    
    if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
        $galleryImages = $_FILES['gallery_images'];
        $captions = $_POST['captions'];
        $uploadDir = '../images/cars/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($galleryImages['name'] as $index => $name) {
            
            if (!empty($name) && $galleryImages['error'][$index] === UPLOAD_ERR_OK) {
                $tmpName = $galleryImages['tmp_name'][$index];
                
                $originalFileName = $name; 
                $destination = $uploadDir . $originalFileName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = 'images/cars/' . $originalFileName;
                    $caption = !empty($captions[$index]) ? $captions[$index] : '';
                    
                    
                    $uploadedImages[] = [
                        'path' => $imagePath,
                        'caption' => $caption,
                        'order' => $index + 1
                    ];
                    
                    if ($index === 0) {
                        $mainImagePath = $imagePath;
                    }
                }
            }
        }
    }

    $sqlCar = "INSERT INTO cars (
        id_brand, name, price, year, color, status, km, fuel_type, 
        engine_cc, horsepower, transmission, engine, previous_owners, 
        license_plate, tax_valid_until, is_flood_free, is_accident_free, 
        hot_deal_label, description, slug, image_url
    ) VALUES (
        :id_brand, :name, :price, :year, :color, :status, :km, :fuel_type,
        :engine_cc, :horsepower, :transmission, :engine, :previous_owners,
        :license_plate, :tax_valid_until, :is_flood_free, :is_accident_free,
        :hot_deal_label, :description, :slug, :main_image
    )";
    
    $stmtCar = $db->prepare($sqlCar);
    $stmtCar->execute([
        'id_brand' => $_POST['id_brand'],
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
        'previous_owners' => $_POST['previous_owners'],
        'license_plate' => $_POST['license_plate'],
        'tax_valid_until' => !empty($_POST['tax_valid_until']) ? $_POST['tax_valid_until'] : null,
        'is_flood_free' => $_POST['is_flood_free'],
        'is_accident_free' => $_POST['is_accident_free'],
        'hot_deal_label' => ($_POST['is_hot_deal'] ?? 'No') === 'Yes' ? 'Hot Deal' : null,
        'description' => $_POST['description'],
        'slug' => $slug,
        'main_image' => $mainImagePath  
    ]);
    
    $carId = $db->lastInsertId();

    if (!empty($uploadedImages) && !empty($carId)) {
        foreach ($uploadedImages as $imageData) {
            $sqlImage = "INSERT INTO car_images (car_id, image_path, caption, display_order) VALUES (:car_id, :image_path, :caption, :display_order)";
            $stmtImage = $db->prepare($sqlImage);
            $stmtImage->execute([
                'car_id' => $carId,
                'image_path' => $imageData['path'],
                'caption' => $imageData['caption'],
                'display_order' => $imageData['order']
            ]);
        }
    }
    
    header('Location: admin-home.php');
    exit;
}


$brandStmt = $db->query("SELECT id, name FROM daftarBrands ORDER BY name");
$brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);

$fuelStmt = $db->query("SELECT name FROM daftarBahanBakar ORDER BY name");
$fuels = $fuelStmt->fetchAll(PDO::FETCH_ASSOC);

$twig = Twig::make('../templates-admin');
echo $twig->render('admin-add-car.twig.html', [
    'brands' => $brands,
    'fuels' => $fuels
]);