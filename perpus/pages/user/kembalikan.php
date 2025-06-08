<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['buku_id'];
    $koneksi->query("UPDATE buku SET tersedia = 1, tanggal_kembali = NULL WHERE id = $id");
}

header("Location: list_buku.php");
?>