<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();
$q = $db->prepare("SELECT * FROM cars WHERE approval_status = 'Approved' ORDER BY name ASC");
$q->execute();
$cars = $q->fetchAll(PDO::FETCH_ASSOC);

$twig = Twig::make('../templates-user');
echo $twig->render('compare-select.twig.html', [
    'cars' => $cars,
    'session' => $_SESSION 
]);
