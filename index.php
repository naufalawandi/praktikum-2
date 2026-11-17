<?php
include_once("config.php");
require_once("auth_check.php");

// Fetch statistics
$total_alat = 0;
$total_merek = 0;
$total_lokasi = 0;

$count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total, COUNT(DISTINCT merek) as total_merek, COUNT(DISTINCT lokasi) as total_lokasi FROM alat");
if ($count_query && $row = mysqli_fetch_assoc($count_query)) {
    $total_alat = $row['total'];
    $total_merek = $row['total_merek'];
    $total_lokasi = $row['total_lokasi'];
}

// Fetch all alat
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMRS - Data Alat Elektromedis</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="elegant/img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="elegant/css/style.min.css">
  <style>
    .card-box {
      background: #ffffff;
      border-radius: 10px;
      padding: 24px;
      box-shadow: 0px 5px 10px rgba(160, 163, 189, 0.1);
      margin-bottom: 24px;
    }
    .darkmode .card-box {
      background: #161624;
      box-shadow: none;
    }
    .btn-action-edit {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      background-color: rgba(255, 172, 50, 0.15);
      color: #ffb648;
      transition: all 0.2s;
    }
    .btn-action-edit:hover {
      background-color: #ffb648;
      color: #ffffff;
    }
    .btn-action-delete {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      background-color: rgba(245, 91, 93, 0.15);
      color: #f26464;
      transition: all 0.2s;
    }
    .btn-action-delete:hover {
      background-color: #f26464;
      color: #ffffff;
    }
    .badge-brand {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      background-color: rgba(88, 135, 255, 0.12);
      color: #5887ff;
    }
    .darkmode .badge-brand {
      background-color: rgba(88, 135, 255, 0.2);
      color: #8bb2ff;
    }
    .posts-table th {
      padding: 14px 16px;
      font-size: 14px;
      font-weight: 600;
    }
    .posts-table td {
      padding: 14px 16px;
      vertical-align: middle;
    }
    .darkmode .posts-table td {
      color: #d6d7e3;
    }
    .tool-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 20px;
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
              <a class="active" href="index.php"><span class="icon home" aria-hidden="true"></span>Data Alat</a>
            </li>
            <li>
              <a href="add.php"><span class="icon document" aria-hidden="true"></span>Tambah Alat</a>
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
            <div class="search-wrapper">
              <i data-feather="search" aria-hidden="true"></i>
              <input type="text" id="searchInput" placeholder="Cari nama alat, merek, lokasi..." onkeyup="filterTable()">
            </div>
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
      <main class="main users chart-page" id="skip-target">
        <div class="container">
          <h2 class="main-title">Data Alat Elektromedis</h2>

          <!-- Stat Cards -->
          <div class="row stat-cards">
            <div class="col-md-6 col-xl-4">
              <article class="stat-cards-item">
                <div class="stat-cards-icon primary">
                  <i data-feather="box" aria-hidden="true"></i>
                </div>
                <div class="stat-cards-info">
                  <p class="stat-cards-info__num"><?php echo $total_alat; ?></p>
                  <p class="stat-cards-info__title">Total Alat Terdata</p>
                </div>
              </article>
            </div>
            <div class="col-md-6 col-xl-4">
              <article class="stat-cards-item">
                <div class="stat-cards-icon success">
                  <i data-feather="tag" aria-hidden="true"></i>
                </div>
                <div class="stat-cards-info">
                  <p class="stat-cards-info__num"><?php echo $total_merek; ?></p>
                  <p class="stat-cards-info__title">Merek Terdaftar</p>
                </div>
              </article>
            </div>
            <div class="col-md-6 col-xl-4">
              <article class="stat-cards-item">
                <div class="stat-cards-icon purple">
                  <i data-feather="map-pin" aria-hidden="true"></i>
                </div>
                <div class="stat-cards-info">
                  <p class="stat-cards-info__num"><?php echo $total_lokasi; ?></p>
                  <p class="stat-cards-info__title">Ruangan / Lokasi</p>
                </div>
              </article>
            </div>
          </div>

          <!-- Table Section -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                <div class="tool-header">
                  <div>
                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">Daftar Inventaris Alat</h3>
                    <p style="font-size: 14px; color: #767676;">Kelola data alat elektromedis rumah sakit</p>
                  </div>
                  <a href="add.php" class="primary-default-btn transparent-btn" style="text-decoration: none;">
                    <i data-feather="plus" style="width: 16px; height: 16px; margin-right: 6px;"></i> Tambah Alat
                  </a>
                </div>

                <div class="users-table table-wrapper">
                  <table class="posts-table">
                    <thead>
                      <tr class="users-table-info">
                        <th style="width: 60px; text-align: center;">No</th>
                        <th>Nama Alat</th>
                        <th>Tahun</th>
                        <th>Merek</th>
                        <th>Lokasi</th>
                        <th style="width: 170px; text-align: center;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php  
                      $i = 1;
                      if ($result && mysqli_num_rows($result) > 0) {
                          while($user_data = mysqli_fetch_array($result)) {         
                              echo "<tr>";
                              echo "<td style='text-align: center; font-weight: 500;'>".$i."</td>";
                              echo "<td><strong style='font-weight: 600;'>".htmlspecialchars($user_data['nama_alat'])."</strong></td>";
                              echo "<td>".htmlspecialchars($user_data['tahun'])."</td>";
                              echo "<td><span class='badge-brand'>".htmlspecialchars($user_data['merek'])."</span></td>";    
                              echo "<td><span style='display: inline-flex; align-items: center; gap: 4px;'><i data-feather='map-pin' style='width: 14px; height: 14px;'></i> ".htmlspecialchars($user_data['lokasi'])."</span></td>";    
                              echo "<td style='text-align: center;'>
                                      <div style='display: inline-flex; gap: 8px;'>
                                        <a href='edit.php?id=".$user_data['id']."' class='btn-action-edit' title='Edit Data'>
                                          <i data-feather='edit-2' style='width: 13px; height: 13px;'></i> Edit
                                        </a>
                                        <a href='delete.php?id=".$user_data['id']."' onclick=\"return confirm('Apakah Anda yakin ingin menghapus alat ini?');\" class='btn-action-delete' title='Hapus Data'>
                                          <i data-feather='trash-2' style='width: 13px; height: 13px;'></i> Hapus
                                        </a>
                                      </div>
                                    </td>";
                              echo "</tr>"; 
                              $i++;       
                          }
                      } else {
                          echo "<tr><td colspan='6' style='text-align: center; padding: 30px; color: #767676;'>Belum ada data alat elektromedis. Silakan klik <strong>Tambah Alat</strong> untuk menambahkan data baru.</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
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
  <script>
    function filterTable() {
      var input = document.getElementById('searchInput');
      var filter = input.value.toLowerCase();
      var table = document.querySelector('.posts-table tbody');
      var rows = table.getElementsByTagName('tr');

      for (var i = 0; i < rows.length; i++) {
        var text = rows[i].textContent || rows[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
          rows[i].style.display = "";
        } else {
          rows[i].style.display = "none";
        }
      }
    }
  </script>
</body>
</html>
