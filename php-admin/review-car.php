<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$id = $_GET['id'] ?? null;
$car = null;

if ($id) {
    $db = DB::getDB();
    $stmt = $db->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $car = $stmt->fetch();

    if ($car) {
        // Tandai sebagai Reviewed jika masih Pending
        if ($car['approval_status'] === 'Pending') {
            $update = $db->prepare("UPDATE cars SET approval_status = 'Reviewed' WHERE id = ?");
            $update->execute([$id]);
            $car['approval_status'] = 'Reviewed';
        }
    }
}

$twig = Twig::make('../templates-admin');
echo $twig->render('review-car.twig.html', ['car' => $car]);
