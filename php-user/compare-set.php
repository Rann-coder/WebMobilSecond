<?php
session_start();

if (!isset($_GET['slot']) || !isset($_GET['id'])) {
    header("Location: compare.php");
    exit;
}

$slot = intval($_GET['slot']);  
$id = intval($_GET['id']);    
if ($slot !== 1 && $slot !== 2) {
    header("Location: compare.php");
    exit;
}

$_SESSION["compare_slot_$slot"] = $id;

header("Location: compare.php");
exit;
