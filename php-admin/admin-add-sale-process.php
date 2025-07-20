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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Akses tidak diizinkan.');
}

$db = DB::getDB();
$db->beginTransaction();

try {
    $carId = $_POST['car_id'];
    $saleStatus = $_POST['status'];

    $customerId = null;
    $guestCustomerName = null;
    if ($_POST['customer_type'] === 'registered' && !empty($_POST['customer_id'])) {
        $customerId = $_POST['customer_id'];
    } elseif ($_POST['customer_type'] === 'guest' && !empty($_POST['guest_customer_name'])) {
        $guestCustomerName = $_POST['guest_customer_name'];
    }

    $params = [
        'transaction_code' => $_POST['transaction_code'],
        'car_id' => $carId,
        'customer_id' => $customerId,
        'guest_customer_name' => $guestCustomerName,
        'showroom_id' => $_POST['showroom_id'],
        'staff_id' => $_POST['staff_id'],
        'sale_date' => $_POST['sale_date'],
        'car_price_at_sale' => !empty($_POST['car_price_at_sale']) ? $_POST['car_price_at_sale'] : 0,
        'admin_fee' => $_POST['admin_fee'] ?: 0,
        'discount' => $_POST['discount'] ?: 0,
        'final_price' => $_POST['final_price'],
        'status' => $saleStatus,
        'notes' => $_POST['notes'],
        'leasing_rule_id' => !empty($_POST['leasing_rule_id']) ? $_POST['leasing_rule_id'] : null,
        'dp_amount' => !empty($_POST['dp_amount']) ? $_POST['dp_amount'] : null,
        'loan_tenor_years' => !empty($_POST['loan_tenor_years']) ? $_POST['loan_tenor_years'] : null,
        'monthly_installment' => !empty($_POST['monthly_installment']) ? $_POST['monthly_installment'] : null,
    ];

    $sqlInsertSale = "
        INSERT INTO penjualan (
            transaction_code, car_id, customer_id, guest_customer_name, showroom_id, staff_id, 
            sale_date, car_price_at_sale, admin_fee, discount, final_price, status, notes,
            leasing_rule_id, dp_amount, loan_tenor_years, monthly_installment
        ) VALUES (
            :transaction_code, :car_id, :customer_id, :guest_customer_name, :showroom_id, :staff_id, 
            :sale_date, :car_price_at_sale, :admin_fee, :discount, :final_price, :status, :notes,
            :leasing_rule_id, :dp_amount, :loan_tenor_years, :monthly_installment
        )";
    
    $stmtInsert = $db->prepare($sqlInsertSale);
    $stmtInsert->execute($params);
    $newCarStatus = '';
        switch ($saleStatus) {
            case 'lunas':
                $newCarStatus = 'Sold Out';
                break;
            case 'dp':
                $newCarStatus = 'Sold Out';
                break;
            case 'hold':
                $newCarStatus = 'Hold';
                break;
        }

    if ($newCarStatus) {
        $sqlUpdateCar = "UPDATE cars SET status = :status WHERE id = :car_id";
        $stmtUpdateCar = $db->prepare($sqlUpdateCar);
        $stmtUpdateCar->execute([
            'status' => $newCarStatus,
            'car_id' => $carId
        ]);
    }

    $db->commit();

    header('Location: admin-sales-history.php');
    exit;

} catch (PDOException $e) {
    $db->rollBack();
    die("Error: Gagal menyimpan data penjualan. " . $e->getMessage());
}