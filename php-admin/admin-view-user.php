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

$userId = $_GET['id'] ?? null;

if (!$userId) {
    die('Error: ID Pengguna tidak ditemukan.');
}

try {
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $twig->render('admin-view-user.twig.html', [
        'user' => $user
    ]);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
