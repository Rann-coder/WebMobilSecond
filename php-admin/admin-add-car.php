<?php

require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$twig = Twig::make('../templates-admin');
echo $twig->render(
    'admin-add-car.twig.html',
    [

    ]
);