<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

// Pastikan hanya admin yang bisa mengakses
// Tambahkan pengecekan session/admin di sini jika diperlukan

// Ambil ID dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $db = DB::getDB();
        
        // Hapus data showroom dari database
        $sql = "DELETE FROM showrooms WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirect kembali ke halaman admin showroom dengan pesan sukses
        header("Location: admin-showroom.php?delete_success=1");
        exit();
    } catch (PDOException $e) {
        // Jika terjadi error, redirect dengan pesan error
        header("Location: admin-showroom.php?delete_error=1");
        exit();
    }
} else {
    // Jika ID tidak valid, redirect kembali
    header("Location: admin-showroom.php");
    exit();
}