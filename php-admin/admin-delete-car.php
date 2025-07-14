<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? null;

    if ($carId) {
        $db = DB::getDB();
        $stmt = $db->prepare("DELETE FROM cars WHERE id = ? AND approval_status IN ('Pending', 'Reviewed')");
        $stmt->execute([$carId]);
    }

    header('Location: admin-home.php');
    exit;
}
