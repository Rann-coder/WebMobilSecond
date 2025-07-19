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

$db = DB::getDB();

$id = $_POST['id'];
$nama = $_POST['nama'];
$jabatan = $_POST['jabatan'];
$email = $_POST['email'];
$telepon = $_POST['telepon'];
$showroom_id = $_POST['showroom_id'];

$stmt = $db->prepare("SELECT foto_url FROM staff_pemasaran WHERE id = ?");
$stmt->execute([$id]);
$currentStaff = $stmt->fetch(PDO::FETCH_ASSOC);
$foto_url = $currentStaff['foto_url'];
$status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'active';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../images/staffs/';
    
    if ($foto_url && file_exists('../' . $foto_url)) {
        unlink('../' . $foto_url);
    }
    
    $filename = uniqid() . '-' . basename($_FILES['foto']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
        $foto_url = 'images/staffs/' . $filename;
    }
}

try {
    $stmt = $db->prepare("
        UPDATE staff_pemasaran 
        SET 
            nama = :nama,
            jabatan = :jabatan,
            email = :email,
            telepon = :telepon,
            foto_url = :foto_url,
            showroom_id = :showroom_id,
            status = :status
        WHERE 
            id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':nama' => $nama,
        ':jabatan' => $jabatan,
        ':email' => $email,
        ':telepon' => $telepon,
        ':foto_url' => $foto_url,
        ':showroom_id' => $showroom_id,
        ':status' => $status 
    ]);

    header('Location: admin-staffs.php?success=1');
    exit;
} catch (PDOException $e) {
    die("Gagal menyimpan data: " . $e->getMessage());
}