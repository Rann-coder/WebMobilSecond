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

use Uph\Mobilsecond\Twig;
use Uph\Mobilsecond\DB;

$db = DB::getDB();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin-showroom.php?error=invalid_id');
    exit;
}

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM showrooms WHERE id = ?");
$stmt->execute([$id]);
$showroom = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$showroom) {
    header('Location: admin-showroom.php?error=not_found');
    exit;
}

$twig = Twig::make('../templates-admin');
echo $twig->render('admin-edit-showroom.twig.html', ['showroom' => $showroom]);