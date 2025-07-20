<?php
session_start();
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

if (!isset($_SESSION['admin_user'])) {
    header('Location: admin-login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin-add-user.php');
    exit();
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$no_hp = trim($_POST['no_hp']);
$alamat = trim($_POST['alamat']);
$role = $_POST['role'];

try {
    $db = DB::getDB();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $userId = 'USR-' . strtoupper(uniqid());

    $sql = "INSERT INTO users (user_id, name, no_hp, password, email, role, alamat) 
            VALUES (:user_id, :name, :no_hp, :password, :email, :role, :alamat)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId,
        ':name' => $name,
        ':no_hp' => $no_hp,
        ':password' => $hashedPassword,
        ':email' => $email,
        ':role' => $role,
        ':alamat' => $alamat
    ]);

    header('Location: admin-manage-users.php');
    exit();

} catch (PDOException $e) {
    $errorMessage = 'Gagal menambahkan pengguna. ';
    if ($e->errorInfo[1] == 1062) { 
        $errorMessage .= 'Email yang Anda masukkan sudah terdaftar.';
    } else {
        $errorMessage .= 'Terjadi kesalahan pada database.';
    }
    
    $_SESSION['notification'] = ['type' => 'error', 'message' => $errorMessage];
    header('Location: admin-add-user.php');
    exit();
}