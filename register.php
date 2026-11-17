<?php
include_once("config.php");

// Jika sudah login, langsung alihkan ke index.php
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";
$nama = "";
$username = "";
$email = "";

if (isset($_POST['submit_register'])) {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nama) || empty($username) || empty($email) || empty($password)) {
        $error = "Semua kolom pendaftaran wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal terdiri dari 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        // Cek apakah username atau email sudah digunakan
        $check_stmt = mysqli_prepare($mysqli, "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        if ($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $error = "Username atau email sudah terdaftar! Silakan gunakan yang lain.";
                mysqli_stmt_close($check_stmt);
            } else {
                mysqli_stmt_close($check_stmt);

                // Hash password menggunakan BCRYPT
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_stmt = mysqli_prepare($mysqli, "INSERT INTO users (nama, username, email, password) VALUES (?, ?, ?, ?)");
                if ($insert_stmt) {
                    mysqli_stmt_bind_param($insert_stmt, "ssss", $nama, $username, $email, $hashed_password);
                    if (mysqli_stmt_execute($insert_stmt)) {
                        header("Location: login.php?msg=registered");
                        exit();
                    } else {
                        $error = "Gagal mendaftarkan akun: " . mysqli_error($mysqli);
                    }
                    mysqli_stmt_close($insert_stmt);
                } else {
                    $error = "Terjadi kesalahan pada server saat membuat akun.";
                }
            }
        } else {
            $error = "Terjadi kesalahan koneksi database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun - SIMRS Elektromedis</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="elegant/img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="elegant/css/style.min.css">
  <style>
    .page-center {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 15px;
      background-color: #F0F2FA;
    }
    .darkmode .page-center {
      background-color: #11111d;
    }
    .sign-up {
      width: 100%;
      max-width: 480px;
      margin: 0 auto;
    }
    .custom-alert {
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      text-align: left;
    }
    .alert-danger {
      background-color: rgba(245, 91, 93, 0.15);
      color: #b02a37;
      border: 1px solid rgba(245, 91, 93, 0.3);
    }
    .darkmode .alert-danger {
      color: #f26464;
    }
    .theme-toggle-fixed {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 100;
    }
  </style>
</head>
<body>
  <div class="layer"></div>

  <!-- Theme Toggle Switcher -->
  <div class="theme-toggle-fixed">
    <button class="theme-switcher gray-circle-btn" type="button" title="Ganti Tema (Terang/Gelap)">
      <span class="sr-only">Switch theme</span>
      <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
      <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
    </button>
  </div>

  <main class="page-center">
    <article class="sign-up">
      <!-- Logo SIMRS -->
      <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 20px;">
        <span class="icon logo" aria-hidden="true"></span>
        <div style="text-align: left;">
          <h2 style="font-size: 22px; font-weight: 700; color: #2f49d1; line-height: 1.1; margin: 0;">SIMRS</h2>
          <span style="font-size: 12px; font-weight: 500; color: #767676;">Elektromedis</span>
        </div>
      </div>

      <h1 class="sign-up__title">Daftar Akun Baru</h1>
      <p class="sign-up__subtitle">Lengkapi formulir di bawah untuk mendaftarkan akun petugas</p>

      <!-- Alert Pesan Error -->
      <?php if (!empty($error)): ?>
        <div class="custom-alert alert-danger">
          <i data-feather="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
          <span><?php echo $error; ?></span>
        </div>
      <?php endif; ?>

      <form class="sign-up-form form" action="register.php" method="POST">
        <label class="form-label-wrapper">
          <p class="form-label">Nama Lengkap</p>
          <input class="form-input" type="text" name="nama" placeholder="Contoh: dr. Budi Santoso" value="<?php echo htmlspecialchars($nama); ?>" required autofocus>
        </label>

        <label class="form-label-wrapper">
          <p class="form-label">Username</p>
          <input class="form-input" type="text" name="username" placeholder="Contoh: budi_elektro" value="<?php echo htmlspecialchars($username); ?>" required>
        </label>

        <label class="form-label-wrapper">
          <p class="form-label">Email</p>
          <input class="form-input" type="email" name="email" placeholder="Contoh: budi@simrs.local" value="<?php echo htmlspecialchars($email); ?>" required>
        </label>

        <label class="form-label-wrapper">
          <p class="form-label">Password</p>
          <input class="form-input" type="password" name="password" placeholder="Minimal 6 karakter" required>
        </label>

        <label class="form-label-wrapper">
          <p class="form-label">Konfirmasi Password</p>
          <input class="form-input" type="password" name="confirm_password" placeholder="Ulangi password di atas" required>
        </label>

        <button type="submit" name="submit_register" class="form-btn primary-default-btn transparent-btn" style="cursor: pointer; margin-top: 10px;">
          <i data-feather="user-plus" style="width: 16px; height: 16px; margin-right: 6px;"></i> Buat Akun
        </button>

        <div style="text-align: center; margin-top: 22px; font-size: 14px;">
          <span style="color: #767676;">Sudah memiliki akun?</span>
          <a href="login.php" style="color: #2f49d1; font-weight: 600; text-decoration: none; margin-left: 4px;">Masuk di sini &rarr;</a>
        </div>
      </form>
    </article>
  </main>

  <!-- Libraries & Scripts -->
  <script src="elegant/plugins/chart.min.js"></script>
  <script src="elegant/plugins/feather.min.js"></script>
  <script src="elegant/js/script.js"></script>
</body>
</html>
