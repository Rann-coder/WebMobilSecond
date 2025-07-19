<?php
$passwordToHash = 'staff01';

$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

echo "Password Asli: " . htmlspecialchars($passwordToHash) . "<br><br>";
echo "Hasil Hash (untuk disalin ke database):<br>";
echo "<textarea rows='3' style='width:100%; margin-top:5px;'>" . htmlspecialchars($hashedPassword) . "</textarea>";

?>