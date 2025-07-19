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
use Uph\Mobilsecond\Twig;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = DB::getDB();

        $sql = "INSERT INTO leasing_rules (
                    leasing_name, admin_fee, min_dp_percentage, max_dp_percentage,
                    interest_rate_1yr, interest_rate_2yr, interest_rate_3yr,
                    interest_rate_4yr, interest_rate_5yr, is_active
                ) VALUES (
                    :leasing_name, :admin_fee, :min_dp, :max_dp,
                    :rate_1, :rate_2, :rate_3, :rate_4, :rate_5, :is_active
                )";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'leasing_name' => $_POST['leasing_name'],
            'admin_fee' => $_POST['admin_fee'],
            'min_dp' => $_POST['min_dp_percentage'],
            'max_dp' => $_POST['max_dp_percentage'],
            'rate_1' => $_POST['interest_rate_1yr'],
            'rate_2' => $_POST['interest_rate_2yr'],
            'rate_3' => $_POST['interest_rate_3yr'],
            'rate_4' => $_POST['interest_rate_4yr'],
            'rate_5' => $_POST['interest_rate_5yr'],
            'is_active' => $_POST['is_active']
        ]);

        header("Location: admin-management.php");
        exit;
    } catch (Exception $e) {
        die("Gagal menyimpan data leasing: " . $e->getMessage());
    }
} else {
    $twig = Twig::make('../templates-admin');
    echo $twig->render(
        'admin-add-leasing.twig.html',
        [
            'page_title' => 'Tambah Mitra Leasing'
        ]
    );
}
