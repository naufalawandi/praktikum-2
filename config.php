<?php
// Mulai session jika belum berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Host database
$databaseHost = 'localhost';

// Nama database
$databaseName = 'simrs';

// Username MySQL
$databaseUsername = 'root';

// Password MySQL
$databasePassword = '';

// Koneksi database
$mysqli = mysqli_connect(
    $databaseHost,
    $databaseUsername,
    $databasePassword,
    $databaseName
);

// Cek koneksi
if (!$mysqli) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Otomatis buat tabel 'users' jika belum ada di database
$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($mysqli, $createUsersTable);

// Buat akun default admin jika tabel users masih kosong
$checkUser = mysqli_query($mysqli, "SELECT id FROM users LIMIT 1");
if ($checkUser && mysqli_num_rows($checkUser) == 0) {
    $defaultPass = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($mysqli, "INSERT INTO users (nama, username, email, password) VALUES ('Administrator SIMRS', 'admin', 'admin@simrs.local', '$defaultPass')");
}
?>
