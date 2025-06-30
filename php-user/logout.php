<?php
session_start();        // Mulai sesi
session_destroy();      // Hapus semua data session

// Redirect ke halaman login (atau halaman utama)
header('Location: home.php');
exit;
