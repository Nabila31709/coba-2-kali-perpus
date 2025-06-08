<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../index.php");
  exit;
}

// Tambah buku
if (isset($_POST['tambah'])) {
  $judul = $_POST['judul'];
  $pengarang = $_POST['pengarang'];
  $penerbit = $_POST['penerbit'];
  $tahun_terbit  = $_POST['tahun_terbit'];
  $genre = $_POST['genre'];
  $tersedia = $_POST['tersedia'];

  $foto = $_FILES['foto']['name'];
  $foto_tmp = $_FILES['foto']['tmp_name'];
  $foto_uniq = time() . '_' . $foto;
  $foto_path = "../uploads/" . $foto_uniq;

  if (!file_exists("../uploads")) mkdir("../uploads", 0777, true);

  if (move_uploaded_file($foto_tmp, $foto_path)) {
    $query = "INSERT INTO buku (foto, judul, pengarang, penerbit, tahun_terbit, genre, tersedia) 
              VALUES ('$foto_uniq', '$judul', '$pengarang', '$penerbit', '$tahun_terbit', '$genre', '$tersedia')";
    mysqli_query($koneksi, $query);
    echo "<script>window.location='buku.php';</script>";
  }
}

// Hapus buku
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto FROM buku WHERE id='$id'"));
  if (file_exists("../uploads/" . $data['foto'])) unlink("../uploads/" . $data['foto']);
  mysqli_query($koneksi, "DELETE FROM buku WHERE id='$id'");
  echo "<script>window.location='buku.php';</script>";
}

// Edit buku
if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $judul = $_POST['judul'];
  $pengarang = $_POST['pengarang'];
  $penerbit = $_POST['penerbit'];
  $tahun_terbit  = $_POST['tahun_terbit'];
  $genre = $_POST['genre'];
  $tersedia = $_POST['tersedia'];

  if ($_FILES['foto']['name']) {
    $foto = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_uniq = time() . '_' . $foto;
    $foto_path = "../uploads/" . $foto_uniq;

    if (move_uploaded_file($foto_tmp, $foto_path)) {
      $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto FROM buku WHERE id='$id'"));
      if (file_exists("../uploads/" . $data['foto'])) unlink("../uploads/" . $data['foto']);
      $updateFoto = ", foto='$foto_uniq'";
    }
  } else {
    $updateFoto = "";
  }

  mysqli_query($koneksi, "UPDATE buku SET judul='$judul', pengarang='$pengarang', penerbit='$penerbit', 
    tahun_terbit='$tahun_terbit', genre='$genre', tersedia='$tersedia' $updateFoto WHERE id='$id'");
  echo "<script>window.location='buku.php';</script>";
}

// Pagination config
$batas = 4;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Pencarian & Query
if (isset($_GET['cari']) && $_GET['cari'] != '') {
  $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
  $jumlah_data = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM buku WHERE judul LIKE '%$cari%'"));
  $total_halaman = ceil($jumlah_data / $batas);
  $query = mysqli_query($koneksi, "SELECT * FROM buku WHERE judul LIKE '%$cari%' ORDER BY id DESC LIMIT $halaman_awal, $batas");
} else {
  $jumlah_data = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM buku"));
  $total_halaman = ceil($jumlah_data / $batas);
  $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC LIMIT $halaman_awal, $batas");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Buku Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-title { font-size: 0.9rem; font-weight: bold; }
    .card-text { font-size: 0.8rem; }
    .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard_admin.php">📚 Manajemen Buku</a>
    <a href="../../logout.php" class="btn btn-outline-primary btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4">
  <h5 class="mb-3">📖 Daftar Buku</h5>
  <button class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Buku</button>

  <!-- Kolom Pencarian -->
  <form class="mb-3 d-flex" method="GET">
    <input type="text" name="cari" class="form-control me-2" placeholder="Cari judul buku..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
    <button class="btn btn-outline-primary" type="submit">Cari</button>
  </form>

  <div class="row">
    <?php while ($buku = mysqli_fetch_assoc($query)) : ?>
    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0">
        <img src="../uploads/<?= $buku['foto'] ?>" class="card-img-top">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= htmlspecialchars($buku['judul']) ?></h5>
          <p class="card-text">
            <?= htmlspecialchars($buku['pengarang']) ?><br>
            <?= htmlspecialchars($buku['penerbit']) ?><br>
            <?= $buku['tahun_terbit'] ?>
          </p>
          <div class="mt-auto d-flex gap-1">
            <button class="btn btn-primary btn-sm w-50" data-bs-toggle="modal" data-bs-target="#editModal<?= $buku['id'] ?>">Edit</button>
            <a href="?hapus=<?= $buku['id'] ?>" class="btn btn-danger btn-sm w-50" onclick="return confirm('Yakin hapus?')">Hapus</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit Buku -->
    <div class="modal fade" id="editModal<?= $buku['id'] ?>" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" enctype="multipart/form-data">
            <div class="modal-header"><h5>Edit Buku</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $buku['id'] ?>">
              <div class="mb-2"><input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required></div>
              <div class="mb-2"><input type="text" class="form-control" name="pengarang" value="<?= htmlspecialchars($buku['pengarang']) ?>" required></div>
              <div class="mb-2"><input type="text" class="form-control" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" required></div>
              <div class="mb-2"><input type="number" class="form-control" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?>" required></div>
              <div class="mb-2"><input type="text" class="form-control" name="genre" value="<?= htmlspecialchars($buku['genre']) ?>" required></div>
              <div class="mb-2"><input type="number" class="form-control" name="tersedia" value="<?= $buku['tersedia'] ?>" required></div>
              <div class="mb-2"><input type="file" class="form-control" name="foto"></div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="edit" class="btn btn-primary btn-sm">Simpan</button>
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endwhile ?>
  </div>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center mt-4">
      <?php if($halaman > 1) : ?>
        <li class="page-item">
          <a class="page-link" href="?halaman=<?= $halaman - 1 ?><?= isset($_GET['cari']) ? '&cari='.urlencode($_GET['cari']) : '' ?>">«</a>
        </li>
      <?php endif; ?>

      <?php for($i = 1; $i <= $total_halaman; $i++) : ?>
        <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
          <a class="page-link" href="?halaman=<?= $i ?><?= isset($_GET['cari']) ? '&cari='.urlencode($_GET['cari']) : '' ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if($halaman < $total_halaman) : ?>
        <li class="page-item">
          <a class="page-link" href="?halaman=<?= $halaman + 1 ?><?= isset($_GET['cari']) ? '&cari='.urlencode($_GET['cari']) : '' ?>">»</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

  <a href="../dashboard_admin.php" class="btn btn-secondary btn-sm mt-3">⬅️ Kembali</a>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header"><h5>Tambah Buku</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-2"><input type="text" class="form-control" name="judul" placeholder="Judul" required></div>
          <div class="mb-2"><input type="text" class="form-control" name="pengarang" placeholder="Pengarang" required></div>
          <div class="mb-2"><input type="text" class="form-control" name="penerbit" placeholder="Penerbit" required></div>
          <div class="mb-2"><input type="number" class="form-control" name="tahun_terbit" placeholder="Tahun" required></div>
          <div class="mb-2"><input type="text" class="form-control" name="genre" placeholder="Genre" required></div>
          <div class="mb-2"><input type="number" class="form-control" name="tersedia" placeholder="tersedia" required></div>
          <div class="mb-2"><input type="file" class="form-control" name="foto" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="tambah" class="btn btn-primary btn-sm">Tambah</button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

