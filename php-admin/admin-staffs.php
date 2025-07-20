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

$sqlStaff = "
SELECT 
    s.id, 
    s.nama, 
    s.jabatan, 
    s.email, 
    s.telepon, 
    s.foto_url,
    s.status,
    sr.name AS showroom_name
FROM 
    staff_pemasaran s
JOIN 
    showrooms sr ON s.showroom_id = sr.id
ORDER BY 
    s.nama ASC;";
$qStaff = $db->prepare($sqlStaff);
$qStaff->execute();
$staffList = $qStaff->fetchAll(PDO::FETCH_ASSOC);


$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-staffs.twig.html',
    [
      'all_staff' => $staffList,
    ]
);