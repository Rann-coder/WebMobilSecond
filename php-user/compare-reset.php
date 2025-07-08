<?php
session_start();

// Hapus session slot perbandingan
unset($_SESSION['compare_slot_1']);
unset($_SESSION['compare_slot_2']);

// Redirect balik ke halaman compare
header('Location: compare.php');
exit;
