<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

$db = DB::getDB();

// Ambil data user
$stmt = $db->prepare("SELECT name, email, no_hp, alamat FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    echo "User tidak ditemukan.";
    exit;
}

// Hitung jumlah mobil yang di-like
$stmtLike = $db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ?");
$stmtLike->execute([$userId]);
$likeCount = $stmtLike->fetchColumn();

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates-user');
$twig = new \Twig\Environment($loader);

// Kirim ke Twig
echo $twig->render('profile.twig.html', [
    'session' => $_SESSION,
    'userData' => $userData,
    'likeCount' => $likeCount // <-- Tambahkan ke Twig
]);
