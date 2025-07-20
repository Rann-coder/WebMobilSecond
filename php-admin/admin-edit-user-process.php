<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => 'Anda harus login sebagai admin untuk mengakses halaman ini.'
    ];
    header('Location: admin-login.php');
    exit(); 
}
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin-manage-users.php');
    exit();
}

$userId = $_POST['user_id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$role = $_POST['role'] ?? 'customer';

$errors = [];
if (empty($name)) { $errors[] = 'Nama lengkap wajib diisi.'; }
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Format email tidak valid.'; }
if (!empty($password) && strlen($password) < 8) { $errors[] = 'Password baru minimal harus 8 karakter.'; }
if (!empty($no_hp) && !ctype_digit($no_hp)) { $errors[] = 'Nomor HP hanya boleh berisi angka.'; }

if (!empty($errors)) {
    $_SESSION['notification'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
    $_SESSION['old_form_data'] = $_POST; 
    header('Location: admin-edit-user.php?id=' . $userId);
    exit();
}

try {
    $db = DB::getDB();
    
    $sql = "UPDATE users SET name = :name, email = :email, no_hp = :no_hp, alamat = :alamat, role = :role";
    $params = [
        ':user_id' => $userId,
        ':name' => $name,
        ':email' => $email,
        ':no_hp' => $no_hp,
        ':alamat' => $alamat,
        ':role' => $role
    ];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
        $params[':password'] = $hashedPassword;
    }

    $sql .= " WHERE user_id = :user_id";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data pengguna berhasil diperbarui!'];
    header('Location: admin-manage-users.php');
    exit();

} catch (PDOException $e) {
    $errorMessage = 'Gagal memperbarui data. ';
    if ($e->errorInfo[1] == 1062) {
        $errorMessage .= 'Email sudah digunakan oleh pengguna lain.';
    } else {
        $errorMessage .= 'Terjadi kesalahan pada database.';
    }
    
    $_SESSION['notification'] = ['type' => 'error', 'message' => $errorMessage];
    $_SESSION['old_form_data'] = $_POST;
    header('Location: admin-edit-user.php?id=' . $userId);
    exit();
}
