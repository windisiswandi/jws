<?php
$host = "localhost";
$user = "root"; // Ganti jika berbeda
$pass = ""; // Ganti jika ada password
$dbname = "jws";

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
