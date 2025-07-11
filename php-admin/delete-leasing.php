<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

if (!isset($_POST['leasing_id'])) {
    die('ID tidak valid');
}

try {
    $db = DB::getDB();
    $stmt = $db->prepare("DELETE FROM leasing_rules WHERE id = ?");
    $stmt->execute([$_POST['leasing_id']]);
    header('Location: admin-management.php');
    exit;
} catch (Exception $e) {
    die('Gagal menghapus: ' . $e->getMessage());
}
