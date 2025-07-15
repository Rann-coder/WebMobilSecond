<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

if (!isset($_GET['id'])) {
    header('Location: admin-staffs.php');
    exit;
}

$db = DB::getDB();

try {
    // Get staff data
    $stmt = $db->prepare("SELECT * FROM staff_pemasaran WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        header('Location: admin-staffs.php');
        exit;
    }

    // Get showrooms for dropdown
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