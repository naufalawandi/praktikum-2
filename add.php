<?php
// include database connection file
include_once("config.php");
require_once("auth_check.php");

$message = "";
$message_type = "";

// Check If form submitted, insert form data into alat table.
if(isset($_POST['Submit'])) {
    $nama_alat = trim($_POST['nama_alat']);
    $tahun = trim($_POST['tahun']);
    $merek = trim($_POST['merek']);
    $lokasi = trim($_POST['lokasi']);

    if(!empty($nama_alat) && !empty($tahun) && !empty($merek) && !empty($lokasi)) {
        // Insert data into table
        $stmt = mysqli_prepare($mysqli, "INSERT INTO alat(nama_alat,tahun,merek,lokasi) VALUES(?,?,?,?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $nama_alat, $tahun, $merek, $lokasi);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Alat elektromedis <strong>" . htmlspecialchars($nama_alat) . "</strong> berhasil ditambahkan!";
                $message_type = "success";
            } else {
                $message = "Gagal menyimpan data: " . mysqli_error($mysqli);
                $message_type = "danger";
            }
            mysqli_stmt_close($stmt);
        } else {
            // fallback direct query
            $nama_safe = mysqli_real_escape_string($mysqli, $nama_alat);
            $tahun_safe = mysqli_real_escape_string($mysqli, $tahun);
            $merek_safe = mysqli_real_escape_string($mysqli, $merek);
            $lokasi_safe = mysqli_real_escape_string($mysqli, $lokasi);

            $result = mysqli_query($mysqli, "INSERT INTO alat(nama_alat,tahun,merek,lokasi) VALUES('$nama_safe','$tahun_safe','$merek_safe','$lokasi_safe')");
            if ($result) {
                $message = "Alat elektromedis <strong>" . htmlspecialchars($nama_alat) . "</strong> berhasil ditambahkan!";
                $message_type = "success";
            } else {
                $message = "Gagal menyimpan data: " . mysqli_error($mysqli);
                $message_type = "danger";
            }
        }
    } else {
        $message = "Mohon lengkapi semua kolom formulir!";
        $message_type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Alat Elektromedis - SIMRS</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="elegant/img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="elegant/css/style.min.css">
  <style>
    .card-box {
      background: #ffffff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0px 5px 10px rgba(160, 163, 189, 0.1);
      margin-bottom: 24px;
    }
    .darkmode .card-box {
      background: #161624;
      box-shadow: none;
    }
    .custom-alert {
      padding: 14px 20px;
      border-radius: 8px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      font-weight: 500;
    }
    .alert-success {
      background-color: rgba(75, 222, 151, 0.15);
      color: #177348;
      border: 1px solid rgba(75, 222, 151, 0.3);
    }
    .darkmode .alert-success {
      color: #4bde97;
    }
    .alert-danger {
      background-color: rgba(245, 91, 93, 0.15);
      color: #b02a37;
      border: 1px solid rgba(245, 91, 93, 0.3);
    }
    .darkmode .alert-danger {
      color: #f26464;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 8px;
      color: #171717;
    }
    .darkmode .form-group label {
      color: #EFF0F6;
    }
    .form-control-custom {
      width: 100%;
      height: 46px;
      padding: 10px 16px;
      border-radius: 8px;
      border: 1px solid #e2e4ed;
      background-color: #f7f8fc;
      font-size: 14px;
      color: #171717;
      box-sizing: border-box;
      transition: all 0.2s;
    }
    .form-control-custom:focus {
      outline: none;
      border-color: #2f49d1;
      background-color: #ffffff;
    }
    .darkmode .form-control-custom {
      background-color: #222235;
      border-color: #37374F;
      color: #EFF0F6;
    }
    .darkmode .form-control-custom:focus {
      border-color: #5887ff;
      background-color: #1b1b2a;
    }
    .btn-container {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-top: 25px;
    }
  </style>
</head>
<body>
  <div class="layer"></div>
  <a class="skip-link sr-only" href="#skip-target">Skip to content</a>
  <div class="page-flex">
    <!-- ! Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-start">
        <div class="sidebar-head">
          <a href="index.php" class="logo-wrapper" title="SIMRS Home">
            <span class="sr-only">Home</span>
            <span class="icon logo" aria-hidden="true"></span>
            <div class="logo-text">
              <span class="logo-title">SIMRS</span>
              <span class="logo-subtitle">Elektromedis</span>
            </div>
          </a>
          <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
            <span class="sr-only">Toggle menu</span>
            <span class="icon menu-toggle" aria-hidden="true"></span>
          </button>
        </div>
        <div class="sidebar-body">
          <ul class="sidebar-body-menu">
            <li>
              <a href="index.php"><span class="icon home" aria-hidden="true"></span>Data Alat</a>
            </li>
            <li>
              <a class="active" href="add.php"><span class="icon document" aria-hidden="true"></span>Tambah Alat</a>
            </li>
            <li>
              <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');" style="color: #f26464;">
                <span class="icon edit" aria-hidden="true" style="filter: hue-rotate(140deg);"></span>Keluar (Logout)
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="sidebar-footer">
        <div class="sidebar-user" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <span class="sidebar-user-img">
              <picture><source srcset="elegant/img/avatar/avatar-illustrated-01.webp" type="image/webp"><img src="elegant/img/avatar/avatar-illustrated-01.png" alt="User avatar"></picture>
            </span>
            <div class="sidebar-user-info">
              <span class="sidebar-user__title"><?php echo htmlspecialchars($_SESSION['nama'] ?? 'Administrator'); ?></span>
              <span class="sidebar-user__subtitle">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'admin'); ?></span>
            </div>
          </div>
          <a href="logout.php" title="Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');" style="color: #f26464; margin-right: 6px; display: flex; align-items: center;">
            <i data-feather="log-out" style="width: 16px; height: 16px;"></i>
          </a>
        </div>
      </div>
    </aside>

    <div class="main-wrapper">
      <!-- ! Main nav -->
      <nav class="main-nav--bg">
        <div class="container main-nav">
          <div class="main-nav-start">
            <a href="index.php" class="secondary-default-btn" style="text-decoration: none; padding: 6px 14px; font-size: 13px;">
              <i data-feather="arrow-left" style="width: 14px; height: 14px; margin-right: 6px;"></i> Kembali ke Data Alat
            </a>
          </div>
          <div class="main-nav-end">
            <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
              <span class="sr-only">Toggle menu</span>
              <span class="icon menu-toggle--gray" aria-hidden="true"></span>
            </button>
            <button class="theme-switcher gray-circle-btn" type="button" title="Switch theme">
              <span class="sr-only">Switch theme</span>
              <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
              <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
            </button>
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');" class="secondary-default-btn" title="Logout" style="text-decoration: none; padding: 6px 12px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; border-color: rgba(245, 91, 93, 0.4); color: #f26464;">
              <i data-feather="log-out" style="width: 14px; height: 14px;"></i>
              <span>Logout</span>
            </a>
          </div>
        </div>
      </nav>

      <!-- ! Main -->
      <main class="main users" id="skip-target">
        <div class="container">
          <h2 class="main-title">Tambah Alat Elektromedis</h2>

          <div class="row">
            <div class="col-lg-8 col-xl-7">
              <div class="card-box">
                <div style="margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                  <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">Formulir Tambah Alat</h3>
                  <p style="font-size: 14px; color: #767676;">Silakan isi informasi alat elektromedis yang akan didaftarkan</p>
                </div>

                <?php if (!empty($message)): ?>
                  <div class="custom-alert alert-<?php echo $message_type; ?>">
                    <i data-feather="<?php echo ($message_type == 'success') ? 'check-circle' : 'alert-circle'; ?>"></i>
                    <div style="flex-grow: 1;">
                      <?php echo $message; ?>
                      <?php if ($message_type == 'success'): ?>
                        <br><a href="index.php" style="text-decoration: underline; font-weight: 600; margin-top: 4px; display: inline-block;">Lihat di Daftar Alat &rarr;</a>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endif; ?>

                <form action="add.php" method="post" name="form1">
                  <div class="form-group">
                    <label for="nama_alat">Nama Alat</label>
                    <input type="text" id="nama_alat" name="nama_alat" class="form-control-custom" placeholder="Contoh: USG 4D, ECG Machine, Patient Monitor" required>
                  </div>

                  <div class="form-group">
                    <label for="tahun">Tahun Perolehan</label>
                    <input type="text" id="tahun" name="tahun" class="form-control-custom" placeholder="Contoh: 2022" required>
                  </div>

                  <div class="form-group">
                    <label for="merek">Merek</label>
                    <input type="text" id="merek" name="merek" class="form-control-custom" placeholder="Contoh: GE Healthcare, Philips, Mindray" required>
                  </div>

                  <div class="form-group">
                    <label for="lokasi">Lokasi / Ruangan</label>
                    <input type="text" id="lokasi" name="lokasi" class="form-control-custom" placeholder="Contoh: Poli Kebidanan, ICU, IGD" required>
                  </div>

                  <div class="btn-container">
                    <button type="submit" name="Submit" value="Add" class="primary-default-btn transparent-btn" style="cursor: pointer;">
                      <i data-feather="plus" style="width: 16px; height: 16px; margin-right: 6px;"></i> Simpan Alat
                    </button>
                    <a href="index.php" class="secondary-default-btn" style="text-decoration: none;">Batal</a>
                  </div>
                </form>
              </div>
            </div>

            <div class="col-lg-4 col-xl-5">
              <div class="card-box" style="background: linear-gradient(135deg, rgba(47, 73, 209, 0.08) 0%, rgba(95, 46, 234, 0.08) 100%);">
                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="info" style="width: 18px; height: 18px; color: #2f49d1;"></i> Petunjuk Pengisian
                </h4>
                <ul style="font-size: 14px; line-height: 1.8; color: #555; padding-left: 20px;">
                  <li>Pastikan nama alat sesuai dengan standar penamaan elektromedis rumah sakit.</li>
                  <li>Tahun perolehan dicatat dalam 4 digit angka (misal: 2023).</li>
                  <li>Merek diisi sesuai dengan vendor atau pabrikan resmi alat.</li>
                  <li>Lokasi disesuaikan dengan unit atau ruangan penempatan alat saat ini.</li>
                </ul>
              </div>
            </div>
          </div>

        </div>
      </main>

      <!-- ! Footer -->
      <footer class="footer">
        <div class="container footer--flex">
          <div class="footer-start">
            <p>2026 © SIMRS - Sistem Informasi Manajemen Rumah Sakit. Elegant Dashboard Theme.</p>
          </div>
          <ul class="footer-end">
            <li><a href="index.php">Data Alat</a></li>
            <li><a href="add.php">Tambah Alat</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </footer>
    </div>
  </div>

  <!-- Chart library -->
  <script src="elegant/plugins/chart.min.js"></script>
  <!-- Icons library -->
  <script src="elegant/plugins/feather.min.js"></script>
  <!-- Custom scripts -->
  <script src="elegant/js/script.js"></script>
</body>
</html>
