<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

session_start();

error_log("car_id = " . print_r($_POST['car_id'], true));

// Harus login
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

$userId = $_SESSION['user']['id'];
$carId = $_POST['car_id'] ?? null;

if (!$carId) {
    echo json_encode(['success' => false, 'message' => 'ID mobil tidak valid.']);
    exit;
}

try {
    $db = DB::getDB();

    // Cek apakah sudah Like
    $stmt = $db->prepare("SELECT * FROM likes WHERE user_id = ? AND car_id = ?");
    $stmt->execute([$userId, $carId]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Sudah Like → maka kita hapus (unlike)
        $delete = $db->prepare("DELETE FROM likes WHERE user_id = ? AND car_id = ?");
        $delete->execute([$userId, $carId]);
        echo json_encode(['success' => true, 'liked' => false, 'message' => 'Like dibatalkan.']);
    } else {
        // Belum Like → kita insert
        $insert = $db->prepare("INSERT INTO likes (user_id, car_id) VALUES (?, ?)");
        $insert->execute([$userId, $carId]);
        echo json_encode(['success' => true, 'liked' => true, 'message' => 'Mobil disukai.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
