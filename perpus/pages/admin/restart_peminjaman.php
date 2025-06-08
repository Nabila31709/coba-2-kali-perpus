<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../index.php");
  exit;
}

// Hapus semua data peminjaman
mysqli_query($koneksi, "DELETE FROM peminjaman");

// Reset semua stok buku jadi tersedia
mysqli_query($koneksi, "UPDATE buku SET tersedia = 1, tanggal_kembali = NULL");

header("Location: daftar_pinjaman.php?pesan=reset");
exit;
?>
