<?php

// Cek apakah dijalankan di localhost
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {

    // Konfigurasi Database Localhost (XAMPP)
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "kelompok8.te24e.my.id";

} else {

    // Konfigurasi Database Hosting (cPanel)
    $host = "localhost";
    $user = "teemyid_sembarangsek";
    $pass = "bebasaja123";
    $db   = "teemyid_kelompok8";

}

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Database gagal terkoneksi: " . mysqli_connect_error());
}
?>