<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin-login.php');
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Email dan password wajib diisi.'];
    header('Location: admin-login.php');
    exit();
}

try {
    $db = DB::getDB();

    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND role = 'admin'");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        unset($admin['password']);
        
        $_SESSION['admin_user'] = $admin;
        
        header('Location: admin-home.php');
        exit();
    } else {
        $_SESSION['notification'] = ['type' => 'error', 'message' => 'Email atau password salah.'];
        header('Location: admin-login.php');
        exit();
    }

} catch (PDOException $e) {
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Terjadi kesalahan database.'];
    header('Location: admin-login.php');
    exit();
}
