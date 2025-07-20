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

$db = DB::getDB();
$twig = Twig::make('../templates-admin');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Error: ID Transaksi tidak valid atau tidak ditemukan.');
}
$saleId = $_GET['id'];

try {
    $sql = "
        SELECT
            p.id, p.transaction_code, p.sale_date, p.final_price, p.status,
            p.car_price_at_sale AS car_price, p.admin_fee, p.discount,
            p.leasing_rule_id, p.loan_tenor_years, p.monthly_installment,
            c.name AS car_name, c.year AS car_year, c.license_plate, c.color, c.image_url AS car_image_url,
            COALESCE(u.name, p.guest_customer_name) AS customer_name,
            u.no_hp AS customer_phone,
            u.email AS customer_email,
            sr.name AS showroom_name,
            sp.nama AS staff_name
        FROM 
            penjualan p
        LEFT JOIN 
            cars c ON p.car_id = c.id
        LEFT JOIN 
            users u ON p.customer_id = u.user_id
        LEFT JOIN 
            showrooms sr ON p.showroom_id = sr.id
        LEFT JOIN 
            staff_pemasaran sp ON p.staff_id = sp.id
        WHERE 
            p.id = :sale_id
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute(['sale_id' => $saleId]);
    $saleDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$saleDetails) {
        die("Error: Transaksi dengan ID '$saleId' tidak ditemukan.");
    }
    $saleDetails['next_due_date'] = null;
    $saleDetails['loan_end_date'] = null;

    if ($saleDetails['status'] === 'dp' && !empty($saleDetails['loan_tenor_years'])) {
        $saleDateObj = new DateTime($saleDetails['sale_date']);
        $saleDateObj->add(new DateInterval('P1M')); 
        $saleDetails['next_due_date'] = $saleDateObj->format('d F Y');

        $endDateObj = new DateTime($saleDetails['sale_date']);
        $endDateObj->add(new DateInterval('P' . $saleDetails['loan_tenor_years'] . 'Y')); 
        $saleDetails['loan_end_date'] = $endDateObj->format('d F Y');
    }

    echo $twig->render('admin-sale-detail.twig.html', [
        'sale' => $saleDetails
    ]);

} catch (Exception $e) {
    die("Error: Terjadi masalah saat memproses data. " . $e->getMessage());
}