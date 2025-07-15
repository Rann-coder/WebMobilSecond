<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();

try {
    $showroomStmt = $db->query("SELECT id, name FROM showrooms ORDER BY name ASC");
    $showrooms = $showroomStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil data showroom: " . $e->getMessage());
}

$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-add-staff.twig.html',
    [
        'showrooms' => $showrooms
    ]
);