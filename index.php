<?php

// 1. KONEKSI DB (Saran: pindahin ke config.php nanti)
require ('config.php');

//$hashBaru = password_hash('admin123', PASSWORD_DEFAULT);
//echo $hashBaru;

// 2. LOGIKA LOGIN/LOGOUT
if (isset($_GET['logout'])) { session_destroy(); header("Location: /"); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $u = $stmt->fetch();
    if ($u && password_verify($_POST['password'], $u['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $u['username'];
        header("Location: /"); exit;
    } else { $error = "Salah!"; }
}

// 3. START UI
include 'layouts/header.php';

if (!isset($_SESSION['logged_in'])) {
    // TAMPILKAN LOGIN
    include 'components/login_form.php';
} else {
    // TAMPILKAN DASHBOARD UTAMA
    echo '<div class="container py-4">';
    
    include 'components/system_monitor.php'; // Kode monitor kamu
    echo '<hr class="my-5">';
    include 'components/file_manager.php';   // Kode file explorer + Python automation
    
    echo '</div>';
}

include 'layouts/footer.php';
