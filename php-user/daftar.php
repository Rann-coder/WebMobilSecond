<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$twig = Twig::make('../templates-user');
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    $db = DB::getDB();

    $check = $db->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        $error = "Email sudah terdaftar. Gunakan email lain.";
    } else {
        $user_id = uniqid('user_'); 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = $db->prepare("INSERT INTO users (user_id, name, no_hp, password, email, alamat) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->execute([$user_id, $name, $no_hp, $hashedPassword, $email, $alamat]);

        $success = "Akun berhasil dibuat. Silakan login.";
    }
}

echo $twig->render('daftar.twig.html', [
    'error' => $error,
    'success' => $success
]);
