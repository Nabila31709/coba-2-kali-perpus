<?php
session_start();
include '../config/koneksi.php';  // <--- harus di sini dulu

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

// Ambil total buku dari tabel buku
$total_buku_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
if (!$total_buku_query) {
  die("Query error: " . mysqli_error($koneksi));
}
$total_buku = mysqli_fetch_assoc($total_buku_query)['total'];

// Karena cuma Nabila, set langsung
$total_pengguna = 1;


?>



<!DOCTYPE html>
<html>

<head>
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      border: 1px solid #ddd;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .navbar {
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📚 Sistem Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="../pages/admin/buku.php">📖 Data Buku</a>
          </li>
        </ul>

        <span class="navbar-text me-3">
          Halo, <strong><?= $_SESSION['username']; ?></strong> 👋
        </span>
        <a href="../logout.php" class="btn btn-outline-primary btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Konten Utama -->
  <div class="container mt-4">
    <div class="alert alert-light border">
      Selamat datang di Dashboard Admin, <strong><?= $_SESSION['username']; ?></strong>! 🎉
    </div>

    <div class="container mt-4">
      <div class="row">

        <!-- Total Buku -->
        <div class="col-md-4 mb-3">
          <div class="card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title">📚 Total Buku</h5>
                <p class="card-text fs-4"><?= $total_buku ?> Buku</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-md-4 mb-3">
          <div class="card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title">👤 Total Pengguna</h5>
                <p class="card-text fs-4"><?= $total_pengguna ?> User</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Laporan Akhir -->
        <div class="col-md-4 mb-3">
          <div class="card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title">🧾 Laporan Akhir</h5>
                <p class="card-text">Lihat atau cetak laporan akhir sistem.</p>
              </div>
              <a href="admin/laporan.php" class="btn btn-outline-primary btn-sm mt-3">Lihat Laporan</a>
            </div>
          </div>
        </div>

      </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>