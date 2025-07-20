<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

session_start();

if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

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
        if (password_verify($passwordInput, $user['password'])) {
        }

        else {
            $error = 'Email atau password salah!';
        }

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

echo $twig->render('login.twig.html', [
    'error' => $error,
    'session' => $_SESSION 
]);
