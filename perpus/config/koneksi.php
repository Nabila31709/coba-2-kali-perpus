<?php
$koneksi = mysqli_connect("localhost", "root", "", "perpus");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>