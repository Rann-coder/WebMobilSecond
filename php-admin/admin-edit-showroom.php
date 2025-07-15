<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\Twig;
use Uph\Mobilsecond\DB;

$db = DB::getDB();

// Ambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin-showroom.php?error=invalid_id');
    exit;
}

$id = $_GET['id'];

// Ambil data showroom dari database
$stmt = $db->prepare("SELECT * FROM showrooms WHERE id = ?");
$stmt->execute([$id]);
$showroom = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$showroom) {
    header('Location: admin-showroom.php?error=not_found');
    exit;
}

// Render halaman edit showroom dengan data showroom
$twig = Twig::make('../templates-admin');
echo $twig->render('admin-edit-showroom.twig.html', ['showroom' => $showroom]);
