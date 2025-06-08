<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['buku_id'];
    $tanggal_kembali = date('Y-m-d', strtotime('+14 days'));
    $koneksi->query("UPDATE buku SET tersedia = 0, tanggal_kembali = '$tanggal_kembali' WHERE id = $id");
}

header("Location:list_buku.php");
?>
