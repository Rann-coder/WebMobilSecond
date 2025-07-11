<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates-admin');
$twig = new \Twig\Environment($loader);

$validUsername = 'admin123';
$validPassword = 'adminpass';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (strlen($username) < 5 || strlen($password) < 5) {
        echo $twig->render('admin-login.twig.html', [
            'error' => 'Username dan password harus minimal 5 karakter.'
        ]);
    } elseif ($username === $validUsername && $password === $validPassword) {
        header('Location: admin-home.php');
        exit;
    } else {
        echo $twig->render('admin-login.twig.html', [
            'error' => 'Username atau password salah.'
        ]);
    }
} else {
    echo $twig->render('admin-login.twig.html');
}
