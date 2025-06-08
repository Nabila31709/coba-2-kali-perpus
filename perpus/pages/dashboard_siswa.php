<?php
session_start();
include '../config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');
if (isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  $id_siswa = $_SESSION['id_siswa'];
} else {
  header("Location: ../index.php");
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar .container-fluid {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .d-flex {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      width: 100%;
    }
    .navbar-text {
      margin-right: 10px;
    }
    .card {
      border: 1px solid #ddd;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📚 Sistem Siswa</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <div class="d-flex justify-content-end w-100">
          <span class="navbar-text me-3">
            Halo, <strong><?= $_SESSION['username']; ?></strong> 👋
          </span>
          <a href="../logout.php" class="btn btn-outline-primary btn-sm">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Konten -->
  <div class="container mt-4">
    <div class="alert alert-light border">
      Selamat datang di Dashboard, <strong><?= $_SESSION['username']; ?></strong>! 🎉
    </div>

<div class="row g-3">
  <!-- Info Data Buku -->
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">📖 Data Buku</h5>
        <p class="card-text">Lihat daftar buku yang tersedia di perpustakaan.</p>
        <a href="../pages/user/list_buku.php" class="btn btn-outline-primary btn-sm">Lihat Buku</a>
      </div>
    </div>
  </div>


  <!-- Card Terakhir Login -->
  <div class="col-md-4">
    <div class="card h-100 p-4 text-center">
      <h5>Terakhir Login</h5>
      <p class="fw-bold"><?= date('d M Y, H:i') ?></p>
    </div>
  </div>
</div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
