<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['reset'])) {
  $username = $_POST['username'];
  $password_baru = md5($_POST['password']);
  $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);


  // Cek apakah username terdaftar
  $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
  if (mysqli_num_rows($cek_user) > 0) {
    mysqli_query($koneksi, "UPDATE users SET password='$password_baru' WHERE username='$username'");
    echo "<script>alert('Password berhasil direset!');window.location='index.php'</script>";
  } else {
    echo "<script>alert('Username tidak ditemukan!');</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
      <div class="card-body">
        <h5 class="text-center">Reset Password</h5>
        <form method="POST">
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" name="reset" class="btn btn-primary w-100">Reset Password</button>
          <a href="index.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

