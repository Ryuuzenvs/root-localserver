<?php
session_start();
// Jika tidak ada session login DAN bukan sedang mengakses halaman login root
// Kita cek jika tidak ada session, arahkan ke index.php root
if (!isset($_SESSION['logged_in'])) {
    // Ambil path sekarang
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Izinkan akses ke index root untuk proses login agar tidak looping
    if ($current_path !== '/' && $current_path !== '/index.php') {
        header("Location: /");
        exit;
    }
}
