<?php
require_once '../vendor/autoload.php';
session_start(); // Diperlukan untuk notifikasi

use Uph\Mobilsecond\DB;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = DB::getDB();
    
    // Ambil data dari form
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $opening_hours = $_POST['opening_hours'] ?? '';
    $status = $_POST['status'] ?? 'active';

    try {
        $sql = "INSERT INTO showrooms (name, address, phone, email, opening_hours, status) 
                VALUES (:name, :address, :phone, :email, :opening_hours, :status)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':phone' => $phone,
            ':email' => $email,
            ':opening_hours' => $opening_hours,
            ':status' => $status
        ]);

        // Set notifikasi sukses
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Showroom ' . htmlspecialchars($name) . ' berhasil ditambahkan!'
        ];

        header('Location: admin-showroom.php');
        exit();

    } catch (PDOException $e) {
        // Set notifikasi error
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Gagal menambahkan showroom: ' . $e->getMessage()
        ];
        header('Location: admin-add-showroom.php');
        exit();
    }
} else {
    header('Location: admin-add-showroom.php');
    exit();
}