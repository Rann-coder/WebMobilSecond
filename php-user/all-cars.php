<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();
$sqlBrands = "SELECT id, name FROM daftarBrands ORDER BY name ASC";
$qBrands = $db->prepare($sqlBrands);
$qBrands->execute();
$listBrands = $qBrands->fetchAll(PDO::FETCH_ASSOC);

$sqlTypes = "SELECT id, name FROM daftarTypes ORDER BY name ASC";
$qTypes = $db->prepare($sqlTypes);
$qTypes->execute();
$listTypes = $qTypes->fetchAll(PDO::FETCH_ASSOC);

$twig = Twig::make('../templates-user');
echo $twig->render(
    'all-cars.twig.html',
    [
      'brands'=> $listBrands,
      'types'=> $listTypes,
      'session' => $_SESSION 
    ]
);