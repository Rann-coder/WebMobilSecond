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

try {
    $db = DB::getDB();
    $stmt = $db->prepare("UPDATE leasing_rules SET 
        leasing_name = ?, admin_fee = ?, is_active = ?, 
        min_dp_percentage = ?, max_dp_percentage = ?, 
        interest_rate_1yr = ?, interest_rate_2yr = ?, 
        interest_rate_3yr = ?, interest_rate_4yr = ?, 
        interest_rate_5yr = ?
        WHERE id = ?");
    
    $stmt->execute([
        $_POST['leasing_name'],
        $_POST['admin_fee'],
        $_POST['is_active'],
        $_POST['min_dp_percentage'],
        $_POST['max_dp_percentage'],
        $_POST['interest_rate_1yr'],
        $_POST['interest_rate_2yr'],
        $_POST['interest_rate_3yr'],
        $_POST['interest_rate_4yr'],
        $_POST['interest_rate_5yr'],
        $_POST['leasing_id']
    ]);

    header('Location: admin-management.php');
    exit;
} catch (Exception $e) {
    die('Gagal update: ' . $e->getMessage());
}
