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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = DB::getDB();
    $carId = $_POST['car_id'];
    $action = $_POST['action'];

    $newStatus = '';
    if ($action === 'approve') {
        $newStatus = 'Approved';
    } elseif ($action === 'reject') {
        $newStatus = 'Rejected';
    }

    if ($newStatus) {
        $sql = "UPDATE cars SET approval_status = :status WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'status' => $newStatus,
            'id' => $carId
        ]);
    }

    header('Location: admin-home.php');
    exit;
}
