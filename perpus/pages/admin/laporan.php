<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

include '../../config/koneksi.php';

// Ambil data buku
$query_buku = mysqli_query($koneksi, "SELECT * FROM buku");

// Hitung total buku
$total_buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"))['total'];

// Karena cuma Nabila, total user = 1
$total_pengguna = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Akhir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3 class="mb-3">📑 Laporan Akhir Sistem</h3>

<div class="mb-4">
  <p><strong>Total Buku:</strong> <?= $total_buku ?></p>
  <p><strong>Total Pengguna:</strong> <?= $total_pengguna ?> </p>
</div>

<table class="table table-bordered">
  <thead class="table-light">
    <tr>
      <th>No</th>
      <th>Judul</th>
      <th>Pengarang</th>
      <th>Penerbit</th>
      <th>Tahun</th>
      <th>Genre</th>
      <th>tersedia</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while($buku = mysqli_fetch_assoc($query_buku)) : ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($buku['judul']) ?></td>
      <td><?= htmlspecialchars($buku['pengarang']) ?></td>
      <td><?= htmlspecialchars($buku['penerbit']) ?></td>
      <td><?= $buku['tahun_terbit'] ?></td>
      <td><?= htmlspecialchars($buku['genre']) ?></td>
      <td><?= $buku['tersedia'] ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<div class="mt-3">
  <button class="btn btn-primary btn-sm" onclick="window.print()">🖨️ Cetak Laporan</button>
  <a href="../dashboard_admin.php" class="btn btn-secondary btn-sm">⬅️ Kembali</a>
</div>

</body>
</html>
