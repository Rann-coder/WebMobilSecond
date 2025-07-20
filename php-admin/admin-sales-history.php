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

try {
    $startDate = $_GET['start_date'] ?? '';
    $endDate = $_GET['end_date'] ?? '';
    $searchQuery = $_GET['search'] ?? '';
    
    $sortableColumns = ['sale_date', 'final_price']; 
    $sortColumn = $_GET['sort'] ?? 'sale_date';
    $sortDirection = $_GET['direction'] ?? 'desc';

    if (!in_array($sortColumn, $sortableColumns)) {
        $sortColumn = 'sale_date';
    }
    if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
        $sortDirection = 'desc';
    }

    $sqlHistory = "
        SELECT
            p.id, p.transaction_code, p.sale_date, p.final_price AS sale_price, p.status,
            c.name AS car_name, c.year AS car_year, c.image_url AS car_image_url,
            COALESCE(u.name, p.guest_customer_name) AS customer_name,
            sr.name AS showroom_name
        FROM penjualan p
        LEFT JOIN cars c ON p.car_id = c.id
        LEFT JOIN users u ON p.customer_id = u.user_id
        LEFT JOIN showrooms sr ON p.showroom_id = sr.id
    ";
    
    $whereConditions = [];
    $params = [];

    if (!empty($startDate) && !empty($endDate)) {
        $whereConditions[] = "p.sale_date BETWEEN :start_date AND :end_date";
        $params['start_date'] = $startDate;
        $params['end_date'] = $endDate;
    }
    if (!empty($searchQuery)) {
        $whereConditions[] = "(c.name LIKE :search OR COALESCE(u.name, p.guest_customer_name) LIKE :search OR p.transaction_code LIKE :search)";
        $params['search'] = '%' . $searchQuery . '%';
    }

    if (!empty($whereConditions)) {
        $sqlHistory .= " WHERE " . implode(' AND ', $whereConditions);
    }
    
    $dbSortColumn = ($sortColumn === 'final_price') ? 'p.final_price' : 'p.sale_date';
    $sqlHistory .= " ORDER BY $dbSortColumn $sortDirection LIMIT 50";

    $qHistory = $db->prepare($sqlHistory);
    $qHistory->execute($params);
    $salesHistory = $qHistory->fetchAll(PDO::FETCH_ASSOC);

    $sqlKpi = "SELECT COALESCE(SUM(final_price), 0) AS total_revenue, COALESCE(COUNT(id), 0) AS cars_sold FROM penjualan WHERE status = 'lunas' AND sale_date BETWEEN :start_date AND :end_date";
    $qKpi = $db->prepare($sqlKpi);
    $qKpi->execute(['start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]);
    $kpiData = $qKpi->fetch(PDO::FETCH_ASSOC);
    $kpiData['avg_price'] = ($kpiData['cars_sold'] > 0) ? ($kpiData['total_revenue'] / $kpiData['cars_sold']) : 0;

    echo $twig->render('admin-sales-history.twig.html', [
        'kpi'           => $kpiData,
        'sales_history' => $salesHistory,
        'start_date'    => $startDate,
        'end_date'      => $endDate,
        'search_query'  => $searchQuery,
        'sort_by'       => $sortColumn,
        'sort_dir'      => $sortDirection
    ]);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}