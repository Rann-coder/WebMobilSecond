<?php

session_start();

require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();

// Ambil ID mobil dari session
$id1 = $_SESSION['compare_slot_1'] ?? null;
$id2 = $_SESSION['compare_slot_2'] ?? null;

$car1 = null;
$car2 = null;

if ($id1) {
    $stmt = $db->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id1]);
    $car1 = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($id2) {
    $stmt = $db->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id2]);
    $car2 = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Render ke Twig
$twig = Twig::make('../templates-user');
echo $twig->render('compare.twig.html', [
    'car1' => $car1,
    'car2' => $car2,
    'session' => $_SESSION // <-- penting agar bisa diakses di Twig
]);