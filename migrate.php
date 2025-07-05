// migrate.php
<?php

// Konfigurasi Database (sesuaikan dengan file config Anda)
$host = 'localhost';
$dbname = 'web_mobil_second2';
$user = 'root';
$pass = ''; // Kosongkan jika tidak ada password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Koneksi database berhasil.\n";

    // Path ke folder migrasi
    $migrationPath = __DIR__ . '/database/migrations/';
    
    // Baca semua file .sql di folder migrasi
    $files = glob($migrationPath . '*.sql');
    sort($files); // Urutkan file berdasarkan nama (0001, 0002, dst.)

    echo "Menjalankan migrasi...\n";
    foreach ($files as $file) {
        $sql = file_get_contents($file);
        $pdo->exec($sql);
        echo " - " . basename($file) . " berhasil dijalankan.\n";
    }
    echo "Semua migrasi selesai.\n\n";

    // Lakukan hal yang sama untuk seeder jika perlu
    $seedPath = __DIR__ . '/database/seeds/';
    $seedFiles = glob($seedPath . '*.sql');
    sort($seedFiles);

    echo "Menjalankan seeder...\n";
    foreach ($seedFiles as $file) {
        $sql = file_get_contents($file);
        $pdo->exec($sql);
        echo " - " . basename($file) . " berhasil dijalankan.\n";
    }
    echo "Semua seeder selesai.\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}