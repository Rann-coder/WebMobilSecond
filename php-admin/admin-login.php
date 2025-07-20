<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\Twig;
$notification = $_SESSION['notification'] ?? null;

unset($_SESSION['notification']);

$twig = Twig::make('../templates-admin');

echo $twig->render('admin-login.twig.html', [
    'notification' => $notification
]);
