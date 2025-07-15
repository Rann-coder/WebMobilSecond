<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

$db = DB::getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'] ?? '';
    $jabatan  = $_POST['jabatan'] ?? '';
    $email    = $_POST['email'] ?? '';
    $telepon  = $_POST['telepon'] ?? '';
    $status   = $_POST['status'] ?? 'inactive';
    $showroom = $_POST['showroom_id'] ?? null;

    if (!$nama || !$jabatan || !$email || !$telepon || !$showroom) {
        die("Data tidak lengkap.");
    }

    // Upload foto
    $fotoUrl = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/staffs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '_' . basename($_FILES['foto']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $fotoUrl = 'uploads/staffs/' . $filename;
        } else {
            die("Gagal mengupload foto.");
        }
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO staff_pemasaran (nama, jabatan, email, telepon, foto_url, status, showroom_id)
            VALUES (:nama, :jabatan, :email, :telepon, :foto_url, :status, :showroom_id)
        ");
        $stmt->execute([
            ':nama'        => $nama,
            ':jabatan'     => $jabatan,
            ':email'       => $email,
            ':telepon'     => $telepon,
            ':foto_url'    => $fotoUrl,
            ':status'      => $status,
            ':showroom_id' => $showroom
        ]);

        header('Location: admin-staffs.php');
        exit;

    } catch (PDOException $e) {
        die("Gagal menyimpan data: " . $e->getMessage());
    }
}
