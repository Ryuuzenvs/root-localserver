<?php
$host = 'localhost'; 
//$host = 'db-server'; 
$db = 'localserver'; 
$user = 'root'; 
$pass = 'root';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (\PDOException $e) { die("DB Error"); }
