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

// Inisialisasi
$db = DB::getDB();
$twig = Twig::make('../templates-admin');

try {
    $userStmt = $db->query("
        SELECT user_id, name, no_hp, email, role, created_at 
        FROM users 
        ORDER BY created_at DESC
    ");
    $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('admin-manage-users.twig.html', [
        'users' => $users
    ]);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
