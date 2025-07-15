<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();

$sqlStaff = "
SELECT 
    s.id, 
    s.nama, 
    s.jabatan, 
    s.email, 
    s.telepon, 
    s.foto_url,
    sr.name AS showroom_name
FROM 
    staff_pemasaran s
JOIN 
    showrooms sr ON s.showroom_id = sr.id
ORDER BY 
    s.nama ASC;";
$qStaff = $db->prepare($sqlStaff);
$qStaff->execute();
$staffList = $qStaff->fetchAll(PDO::FETCH_ASSOC);


$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-staffs.twig.html',
    [
      'all_staff' => $staffList,
    ]
);