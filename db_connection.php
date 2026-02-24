<?php
session_start();

// --- KONFIGURASI DATABASE ---
//$host = 'db-server';
$host = 'localhost';
//$db   = 'localserver';
$db   = 'localserver';
$user = 'root'; // sesuaikan
$pass = 'root';     // sesuaikan
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

