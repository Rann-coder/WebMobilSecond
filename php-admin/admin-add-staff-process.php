<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

$db = DB::getDB();

// Ambil data dari form
$nama = $_POST['nama'];
$jabatan = $_POST['jabatan'];
$email = $_POST['email'];
$telepon = $_POST['telepon'];
$showroom_id = $_POST['showroom_id'];

// Handle foto upload
$foto_url = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/staff/';
    $filename = uniqid() . '-' . basename($_FILES['foto']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
        $foto_url = 'uploads/staff/' . $filename;
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
