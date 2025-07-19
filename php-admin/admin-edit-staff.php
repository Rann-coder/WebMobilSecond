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

if (!isset($_GET['id'])) {
    header('Location: admin-staffs.php');
    exit;
}

$db = DB::getDB();

try {
    $stmt = $db->prepare("SELECT * FROM staff_pemasaran WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        header('Location: admin-staffs.php');
        exit;
    }

    $showroomStmt = $db->query("SELECT id, name FROM showrooms ORDER BY name ASC");
    $showrooms = $showroomStmt->fetchAll(PDO::FETCH_ASSOC);

    $twig = Twig::make('../templates-admin');
    echo $twig->render(
        'admin-edit-staff.twig.html',
        [
            'staff' => $staff,
            'showrooms' => $showrooms
        ]
    );

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}