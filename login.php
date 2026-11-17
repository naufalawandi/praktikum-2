<?php
include_once("config.php");

// Jika sudah login, langsung alihkan ke index.php
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";
$username_input = "";

// Cek notifikasi dari logout atau registrasi
if (isset($_GET['msg']) && $_GET['msg'] === 'logout') {
    $success = "Anda telah berhasil keluar dari sistem.";
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $success = "Registrasi berhasil! Silakan masuk dengan akun baru Anda.";
}

if (isset($_POST['submit_login'])) {
    $username_input = trim($_POST['username']);
    $password_input = trim($_POST['password']);

    if (empty($username_input) || empty($password_input)) {
        $error = "Mohon isi username/email dan password!";
    } else {
        // Cari pengguna berdasarkan username ATAU email
        $stmt = mysqli_prepare($mysqli, "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username_input, $username_input);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if ($res && $user = mysqli_fetch_assoc($res)) {
                // Verifikasi password (dukung password_hash, dan fallback md5 / plain untuk fleksibilitas)
                if (password_verify($password_input, $user['password']) || $password_input === $user['password'] || md5($password_input) === $user['password']) {
                    $_SESSION['login'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['email'] = $user['email'];

                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Password yang Anda masukkan salah!";
                }
            } else {
                $error = "Akun dengan username atau email tersebut tidak ditemukan!";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Terjadi kesalahan query pada server database.";
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
  <title>Login - SIMRS Elektromedis</title>
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
      max-width: 440px;
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
    .alert-success {
      background-color: rgba(75, 222, 151, 0.15);
      color: #177348;
      border: 1px solid rgba(75, 222, 151, 0.3);
    }
    .darkmode .alert-success {
      color: #4bde97;
    }
    .theme-toggle-fixed {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 100;
    }
    .credential-hint {
      margin-top: 20px;
      padding: 12px 16px;
      background: rgba(47, 73, 209, 0.08);
      border-radius: 8px;
      font-size: 13px;
      color: #444;
      line-height: 1.6;
    }
    .darkmode .credential-hint {
      background: rgba(47, 73, 209, 0.2);
      color: #b8c4f5;
    }
    .credential-hint code {
      background: #ffffff;
      padding: 2px 6px;
      border-radius: 4px;
      font-weight: 600;
      color: #2f49d1;
    }
    .darkmode .credential-hint code {
      background: #1e1e30;
      color: #8bb2ff;
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

      <h1 class="sign-up__title">Selamat Datang</h1>
      <p class="sign-up__subtitle">Masuk untuk mengelola data alat elektromedis rumah sakit</p>

      <!-- Alert Pesan Sukses -->
      <?php if (!empty($success)): ?>
        <div class="custom-alert alert-success">
          <i data-feather="check-circle" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
          <span><?php echo $success; ?></span>
        </div>
      <?php endif; ?>

      <!-- Alert Pesan Error -->
      <?php if (!empty($error)): ?>
        <div class="custom-alert alert-danger">
          <i data-feather="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
          <span><?php echo $error; ?></span>
        </div>
      <?php endif; ?>

      <form class="sign-up-form form" action="login.php" method="POST">
        <label class="form-label-wrapper">
          <p class="form-label">Username atau Email</p>
          <input class="form-input" type="text" name="username" placeholder="Masukkan username atau email" value="<?php echo htmlspecialchars($username_input); ?>" required autofocus>
        </label>

        <label class="form-label-wrapper">
          <p class="form-label">Password</p>
          <input class="form-input" type="password" name="password" placeholder="Masukkan password" required>
        </label>

        <button type="submit" name="submit_login" class="form-btn primary-default-btn transparent-btn" style="cursor: pointer; margin-top: 10px;">
          <i data-feather="log-in" style="width: 16px; height: 16px; margin-right: 6px;"></i> Masuk ke Akun
        </button>

        <div style="text-align: center; margin-top: 22px; font-size: 14px;">
          <span style="color: #767676;">Belum memiliki akun?</span>
          <a href="register.php" style="color: #2f49d1; font-weight: 600; text-decoration: none; margin-left: 4px;">Daftar di sini &rarr;</a>
        </div>
      </form>

      <!-- Info Akun Bawaan / Demo -->
      <div class="credential-hint">
        <div style="font-weight: 600; margin-bottom: 4px;">Informasi Akun Default:</div>
        Username: <code>admin</code> | Password: <code>admin123</code>
      </div>
    </article>
  </main>

  <!-- Libraries & Scripts -->
  <script src="elegant/plugins/chart.min.js"></script>
  <script src="elegant/plugins/feather.min.js"></script>
  <script src="elegant/js/script.js"></script>
</body>
</html>
