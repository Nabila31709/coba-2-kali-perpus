<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
  $data = mysqli_fetch_array($query);

if (isset($_POST['login'])) {
  $username = $_POST['username'];
$password = md5($_POST['password']);
  $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");

  $data = mysqli_fetch_array($query);

  if ($data) {
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['id_siswa'] = $data['id_siswa'];

    if ($data['role'] == 'admin') {
      header("Location: pages/dashboard_admin.php");
    } else {
  header("Location: pages/dashboard_siswa.php");
    }
  } else {
    echo "<script>alert('Login gagal! Username atau Password salah');</script>";
  }
}

}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login Sistem Manajemen Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
      font-weight: 600;
      margin-bottom: 20px;
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <h2>Selamat Datang </h2>
        <div class="card p-4">
          <div class="card-body">
            <img src="perpus.jpg" alt="Logo" class="img-fluid mb-3" style="width: 100px; height: auto;">
            <h4 class="text-center mb-4">Login Sistem</h4>
            <form method="POST">
              <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
              </div>
              <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
              </div>
              <div class="d-grid">
                <button class="btn btn-primary" name="login">Login</button>
              </div>
            </form>
          </div>
        </div>
        <p class="mt-3">
          <a href="reset_password.php">Lupa Password?</a>
        </p>

        <p class="mt-3 text-muted">© <?= date('Y') ?> ayo pinjam buku</p>
      </div>
    </div>
  </div>

</body>

</html>