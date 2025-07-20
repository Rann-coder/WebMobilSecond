<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

session_start();

if (!isset($_SESSION['user'])) {
    die("Anda harus login terlebih dahulu.");
}

$userId = $_SESSION['user']['id'];

try {
    $db = DB::getDB();

    $sql = "SELECT c.*
        FROM likes l
        JOIN cars c ON l.car_id = c.id
        WHERE l.user_id = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION['user']['id']]);
    $likedCars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}

$twig = Twig::make('../templates-user');
echo $twig->render('liked-cars.twig.html', [
    'likedCars' => $likedCars,
    'session' => $_SESSION
]);
