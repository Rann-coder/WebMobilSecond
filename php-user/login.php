<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

session_start();

$twig = Twig::make('../templates-user');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $passwordInput = $_POST['password'];

    $db = DB::getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Jika password sudah ter-hash
        if (password_verify($passwordInput, $user['password'])) {
            // Login sukses
        } 
        // Jika password belum di-hash, cocokkan langsung dan update
        else if ($passwordInput === $user['password']) {
            $hashed = password_hash($passwordInput, PASSWORD_DEFAULT);
            $update = $db->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update->execute([$hashed, $user['user_id']]);
            // Login sukses
        } 
        else {
            $error = 'Email atau password salah!';
        }

        // Jika login sukses (salah satu dari atas), set session & redirect
        if (!$error) {
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
            header('Location: home.php');
            exit;
        }
    } else {
        $error = 'Email atau password salah!';
    }
}

echo $twig->render('login.twig.html', ['error' => $error]);
