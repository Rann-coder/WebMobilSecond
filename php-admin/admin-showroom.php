<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();

// Tambahkan pesan notifikasi
$messages = [];
if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1) {
    $messages[] = ['type' => 'success', 'text' => 'Showroom berhasil dihapus!'];
}
if (isset($_GET['delete_error']) && $_GET['delete_error'] == 1) {
    $messages[] = ['type' => 'error', 'text' => 'Gagal menghapus showroom!'];
}

$sqlShowrooms = "SELECT id, name, address, phone, opening_hours, status FROM showrooms ORDER BY name ASC";
$qShowrooms = $db->prepare($sqlShowrooms);
$qShowrooms->execute();
$listShowrooms = $qShowrooms->fetchAll(PDO::FETCH_ASSOC);

$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-showroom.twig.html',
    [
        'showrooms' => $listShowrooms,
        'messages' => $messages
    ]
);