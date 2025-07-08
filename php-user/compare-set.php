<?php
session_start();

// Pastikan dua parameter ada
if (!isset($_GET['slot']) || !isset($_GET['id'])) {
    header("Location: compare.php");
    exit;
}

$slot = intval($_GET['slot']);  // slot = 1 atau 2
$id = intval($_GET['id']);      // ID mobil yang dipilih

// Validasi slot
if ($slot !== 1 && $slot !== 2) {
    header("Location: compare.php");
    exit;
}

// Simpan ke session
$_SESSION["compare_slot_$slot"] = $id;

// Redirect kembali ke halaman perbandingan
header("Location: compare.php");
exit;
