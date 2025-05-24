<?php
session_start();
$conn = new mysqli("localhost", "root", "", "login_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = trim($_POST['nama']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $nama);
  $stmt->execute();
  $stmt->bind_result($hashedPassword);

  if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
    $_SESSION['username'] = $nama;
    header("Location: menu.html");
    exit;
  } else {
    $error = "Nama atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta charset="UTF-8">
  <title>Login SAPD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 100vh;">

<div class="text-center px-3" style="max-width: 400px; width: 100%;">
  <img src="assets/brand/sapd.png" alt="SAPD Logo" width="120" class="mb-4">
  <h1 class="mb-3">Login SAPD</h1>

  <form method="POST">
    <input type="text" name="nama" class="form-control mb-3 text-center" placeholder="Nama Anda" required>
    <input type="password" name="password" class="form-control mb-3 text-center" placeholder="Password" required>
    <button type="submit" class="btn btn-primary w-100">Masuk</button>
  </form>

  <a href="register.php" class="btn btn-outline-light w-100 mt-2">Daftar Akun Baru</a>

  <?php if (!empty($error)) : ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '<?= $error ?>',
        confirmButtonText: 'Coba Lagi'
      });
    </script>
  <?php endif; ?>
</div>
</body>
</html>
