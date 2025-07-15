<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\Twig;

$twig = Twig::make('../templates-admin');
echo $twig->render('admin-add-showroom.twig.html');