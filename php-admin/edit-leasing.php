<?php
// File: edit-leasing.php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

try {
    $db = DB::getDB();
    $stmt = $db->prepare("SELECT * FROM leasing_rules WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $leasing = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$leasing) {
        die("Data leasing tidak ditemukan.");
    }

    $twig = Twig::make('../templates-admin');
    echo $twig->render('admin-edit-leasing.twig.html', ['leasing' => $leasing]);
} catch (Exception $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}