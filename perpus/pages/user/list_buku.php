 <?php
session_start();
include '../../config/koneksi.php';

if (isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  $id_siswa = $_SESSION['id_siswa'];
} else {
  header("Location: ../index.php");
  exit;
}
// Pagination
$batas = 6;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : "";

// Hitung total data
if ($cari != "") {
  $query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE judul LIKE '%$cari%'");
} else {
  $query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
}
$total_data = mysqli_fetch_assoc($query_total)['total'];
$total_halaman = ceil($total_data / $batas);

// Ambil data buku
if ($cari != "") {
  $query_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE judul LIKE '%$cari%' ORDER BY judul ASC LIMIT $halaman_awal, $batas");
} else {
  $query_buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY judul ASC LIMIT $halaman_awal, $batas");
}

if (!$query_buku) {
  die('Query Error: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Daftar Buku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      border: 1px solid #ddd;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .ratio-9x16 {
      aspect-ratio: 9 / 16;
      overflow: hidden;
      border-radius: 0.5rem 0.5rem 0 0;
    }

    .ratio-9x16 img {
      object-fit: cover;
      width: 100%;
      height: 100%;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📚 Sistem Siswa</a>
      <div class="d-flex justify-content-end w-100">
        <span class="navbar-text me-3">
          Halo, <strong><?= $_SESSION['username']; ?></strong> 👋
        </span>
        <a href="../../logout.php" class="btn btn-outline-primary btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Form Pencarian -->
  <div class="d-flex justify-content-center my-3">
    <form method="GET" class="d-flex" style="max-width: 400px; width: 100%;">
      <input type="text" name="cari" class="form-control me-2" placeholder="Cari buku..." value="<?= $cari ?>">
      <button type="submit" class="btn btn-outline-primary btn-sm">Cari</button>
    </form>
  </div>

  <!-- Daftar Buku -->
  <div class="container">
    <div class="row">
      <?php if (mysqli_num_rows($query_buku) > 0): ?>
        <?php while ($buku = mysqli_fetch_assoc($query_buku)): ?>
          <div class="col-6 col-md-3 col-lg-2 mb-3">
            <div class="card h-100 shadow-sm">
              <div class="ratio-9x16">
                <img src="../uploads/<?= $buku['foto']; ?>" alt="Cover Buku">
              </div>
              <div class="p-2">
                <h6 class="mb-2"><?= $buku['judul'] ?></h6>
                <p class="text-muted small">tersedia: <?= $buku['tersedia'] ?></p>
               <?php if ($buku['tersedia'] > 0): ?>
  <form method="POST" action="pinjam.php" class="form-pinjam">
    <input type="hidden" name="buku_id" value="<?= $buku['id'] ?>">
    <button type="button" class="btn btn-primary btn-sm w-100 mb-1 btn-pinjam">
      <i class="fas fa-book-reader me-1"></i> Pinjam
    </button>
  </form>


                <?php else: ?>
                  <form method="POST" action="kembalikan.php">
                    <input type="hidden" name="buku_id" value="<?= $buku['id'] ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100 mb-1">
                      <i class="fas fa-undo-alt me-1"></i> Kembalikan
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-warning">Data buku tidak ditemukan.</div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Tombol Kembali -->
    <div class="d-flex justify-content-center my-3">
      <a href="../dashboard_siswa.php" class="btn btn-secondary btn-sm mt-3">⬅️ Kembali</a>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center mt-3">
        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
          <li class="page-item <?= ($i == $halaman) ? 'active' : ''; ?>">
            <a class="page-link" href="?halaman=<?= $i; ?>&cari=<?= $cari ?>"><?= $i; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.querySelectorAll('.btn-pinjam').forEach(function(button) {
    button.addEventListener('click', function() {
      const form = this.closest('.form-pinjam');
      const today = new Date();
      const kembali = new Date();
      kembali.setDate(today.getDate() + 7);

      const tanggalKembali = kembali.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      if (confirm(`Buku ini harus dikembalikan sebelum tanggal ${tanggalKembali}. Lanjutkan pinjam?`)) {
        form.submit();
      }
    });
  });
</script>

</body>

</html> 