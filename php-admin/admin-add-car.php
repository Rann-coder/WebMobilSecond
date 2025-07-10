<?php
require_once '../vendor/autoload.php';

use Uph\Mobilsecond\DB;
use Uph\Mobilsecond\Twig;

$db = DB::getDB();

function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return $text . '-' . uniqid(); // Biar tetap unik
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = slugify($_POST['name']);

    $sql = "INSERT INTO cars 
        (id_brand, name, price, slug, approval_status)
        VALUES (:id_brand, :name, :price, :slug, 'Pending')";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        'id_brand' => $_POST['id_brand'],
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'slug' => $slug
    ]);

    header('Location: admin-home.php');
    exit;
}

// Ambil data brands
$brandStmt = $db->query("SELECT id, name FROM daftarBrands ORDER BY name");
$brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data bahan bakar


// Tampilkan halaman dengan data brands dan fuels
$twig = Twig::make('../templates-admin');
echo $twig->render('admin-add-car.twig.html', [
    'brands' => $brands,
    'fuels' => $fuels
]);
