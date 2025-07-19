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

$sqlShowrooms = "SELECT id, name, address, phone, opening_hours, status, image_url, email FROM showrooms ORDER BY name ASC";
$qShowrooms = $db->prepare($sqlShowrooms);
$qShowrooms->execute();
$listShowrooms = $qShowrooms->fetchAll(PDO::FETCH_ASSOC);


$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-showroom.twig.html',
    [
      'showrooms'=> $listShowrooms,
    ]
);