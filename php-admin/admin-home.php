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

function formatRevenue($number) {
    if ($number >= 1000000000) {
        return 'Rp ' . number_format($number / 1000000000, 1, ',', '.') . ' M';
    }
    if ($number >= 1000000) {
        return 'Rp ' . number_format($number / 1000000, 1, ',', '.') . ' Jt';
    }
    return 'Rp ' . number_format($number, 0, ',', '.');
}

try {
    $db = DB::getDB();

    $sql = "SELECT id, name, price, created_at, slug, approval_status 
            FROM cars 
            WHERE approval_status IN ('Pending', 'Reviewed') 
            ORDER BY created_at DESC";
    
    $stmt = $db->query($sql);
    $pendingCars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalCars = $db->query("SELECT COUNT(id) FROM cars WHERE status = 'Available' AND approval_status = 'Approved'")->fetchColumn();
    $totalShowrooms = $db->query("SELECT COUNT(id) FROM showrooms")->fetchColumn();
    
    $salesThisMonth = $db->query("
        SELECT COUNT(id) 
        FROM penjualan 
        WHERE status IN ('lunas', 'dp') AND MONTH(sale_date) = MONTH(CURRENT_DATE()) AND YEAR(sale_date) = YEAR(CURRENT_DATE())
    ")->fetchColumn();
    
    $revenueThisMonthRaw = $db->query("
        SELECT SUM(final_price) 
        FROM penjualan 
        WHERE status = 'lunas' AND MONTH(sale_date) = MONTH(CURRENT_DATE()) AND YEAR(sale_date) = YEAR(CURRENT_DATE())
    ")->fetchColumn();

    $stats = [
        'total_cars' => $totalCars,
        'sales_this_month' => $salesThisMonth,
        'total_showrooms' => $totalShowrooms,
        'revenue_this_month' => formatRevenue($revenueThisMonthRaw ?: 0)
    ];

} catch (Exception $e) {
    $pendingCars = []; 
    $stats = [
        'total_cars' => 0,
        'sales_this_month' => 0,
        'total_showrooms' => 0,
        'revenue_this_month' => 'Rp 0'
    ];
}

$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-home.twig.html',
    [
        'pendingCars' => $pendingCars,
        'stats' => $stats, 
        'active_menu' => 'dashboard'
    ]
);