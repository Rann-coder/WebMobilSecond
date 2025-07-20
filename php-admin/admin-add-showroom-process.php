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
    header('Location: admin-add-showroom.php');
    exit();
}

$db = DB::getDB();

$imageUrl = null; 

if (isset($_FILES['showroom_image']) && $_FILES['showroom_image']['error'] === UPLOAD_ERR_OK) {
    
    $uploadDir = '../images/showrooms/'; 

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0775, true); 
    }
    $file = $_FILES['showroom_image'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueFileName = 'showroom-' . uniqid() . '.' . $fileExtension;
    $destination = $uploadDir . $uniqueFileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $imageUrl = 'images/showrooms/' . $uniqueFileName;
    } else {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Gagal meng-upload gambar showroom.'
        ];
        header('Location: admin-add-showroom.php');
        exit();
    }
}

$name = htmlspecialchars($_POST['name'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$opening_hours = htmlspecialchars($_POST['opening_hours'] ?? '');
$status = $_POST['status'] ?? 'active';

if (empty($name)) {
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Nama showroom wajib diisi.'];
    header('Location: admin-add-showroom.php');
    exit();
}

try {
    $sql = "INSERT INTO showrooms (name, address, phone, email, opening_hours, status, image_url) 
            VALUES (:name, :address, :phone, :email, :opening_hours, :status, :image_url)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':address' => $address,
        ':phone' => $phone,
        ':email' => $email,
        ':opening_hours' => $opening_hours,
        ':status' => $status,
        ':image_url' => $imageUrl 
    ]);

    $_SESSION['notification'] = [
        'type' => 'success',
        'message' => 'Showroom "' . $name . '" berhasil ditambahkan!'
    ];

    header('Location: admin-showroom.php');
    exit();

} catch (PDOException $e) {
    $errorMessage = 'Gagal menambahkan showroom. ';
    if ($e->errorInfo[1] == 1062) { 
        $errorMessage .= 'Nama showroom sudah ada.';
    } else {
        $errorMessage .= $e->getMessage();
    }
    
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => $errorMessage
    ];
    
    header('Location: admin-add-showroom.php');
    exit();
}