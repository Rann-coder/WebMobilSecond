<?php
session_start();

unset($_SESSION['compare_slot_1']);
unset($_SESSION['compare_slot_2']);
header('Location: compare.php');
exit;
