<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$twig = Twig::make('../templates-user');

$db = DB::getDB();

$car1 = null;
$car2 = null;
$error = null;

// Ambil ID mobil dari URL
$car1_id = $_GET['car1'] ?? null;
$car2_id = $_GET['car2'] ?? null;

try {
    if ($car1_id) {
        $stmt1 = $db->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt1->execute([$car1_id]);
        $car1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    }

    if ($car2_id) {
        $stmt2 = $db->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt2->execute([$car2_id]);
        $car2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    }

    if (!$car1 && !$car2) {
        $error = "Tidak ada mobil yang dipilih untuk dibandingkan.";
    }
} catch (PDOException $e) {
    $error = "Terjadi kesalahan saat mengambil data mobil.";
}

echo $twig->render('compare.twig.html', [
    'car1' => $car1,
    'car2' => $car2,
    'error' => $error
]);
