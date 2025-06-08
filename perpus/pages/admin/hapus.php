<?php
session_start();
include '../../config/koneksi.php'; 

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../index.php");
  exit;
}

// Cek apakah ada parameter 'id' yang dikirimkan melalui URL
if (isset($_GET['id'])) {
  $id_buku = $_GET['id'];

  // Ambil nama file foto dari database berdasarkan ID buku
  $query = mysqli_query($koneksi, "SELECT foto FROM buku WHERE id = '$id_buku'");
  if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $foto = $data['foto'];

    // Hapus file foto dari server (jika file ada)
    if (file_exists("../uploads/" . $foto)) {
      unlink("../uploads/" . $foto);
    }

    // Hapus data buku dari database
    $delete_query = "DELETE FROM buku WHERE id = '$id_buku'";
    if (mysqli_query($koneksi, $delete_query)) {
      echo "<script>alert('Buku berhasil dihapus!'); window.location = 'list_buku.php';</script>";
    } else {
      echo "<script>alert('Gagal menghapus buku!'); window.location = 'list_buku.php';</script>";
    }
  } else {
    echo "<script>alert('Buku tidak ditemukan!'); window.location = 'list_buku.php';</script>";
  }
} else {
  echo "<script>alert('ID buku tidak ditemukan!'); window.location = 'list_buku.php';</script>";
}
?>
