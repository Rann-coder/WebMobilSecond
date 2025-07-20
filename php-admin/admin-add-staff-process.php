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

$nama = $_POST['nama'];
$jabatan = $_POST['jabatan'];
$email = $_POST['email'];
$telepon = $_POST['telepon'];
$showroom_id = $_POST['showroom_id'];

$foto_url = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../images/staffs/';
    $filename = uniqid() . '-' . basename($_FILES['foto']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
        $foto_url = 'images/staffs/' . $filename;
    }
}

try {
    $stmt = $db->prepare("
        INSERT INTO staff_pemasaran (nama, jabatan, email, telepon, foto_url,  showroom_id)
        VALUES (:nama, :jabatan, :email, :telepon, :foto_url,  :showroom_id)
    ");

    $stmt->execute([
        ':nama' => $nama,
        ':jabatan' => $jabatan,
        ':email' => $email,
        ':telepon' => $telepon,
        ':foto_url' => $foto_url,
        ':showroom_id' => $showroom_id
    ]);

    header('Location: admin-staffs.php?success=1');
    exit;
} catch (PDOException $e) {
    die("Gagal menyimpan data: " . $e->getMessage());
}