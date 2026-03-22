<?php
session_start();

// 1. Ambil path sekarang
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. DAFTAR WHITELIST (File yang boleh diakses tanpa Login)
$whitelist = ['/', '/index.php', '/gold-tracker/api.php', '/api.php'];

// 3. Jika TIDAK ada session DAN path sekarang TIDAK ada di whitelist
if (!isset($_SESSION['logged_in']) && !in_array($current_path, $whitelist)) {
    header("Location: /");
    exit;
}
