<?php
//session_start();
if (!isset($_SESSION['logged_in'])) die("Access Denied");

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$project_id = $_GET['id'] ?? 0;
$user_args = $_GET['args'] ?? []; // Array dari input form

// 1. Ambil data proyek dari DB
// (Gunakan koneksi PDO yang sama dengan index.php kamu)
require 'db_connection.php'; // Pisahkan koneksi PDO ke file tersendiri agar bisa di-require

$stmt = $pdo->prepare("SELECT * FROM py_projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    echo "data: Proyek tidak ditemukan.\n\n";
    exit;
}

// 2. Susun Command
$run_sh = "/var/www/html/run.sh";
//$run_sh = "/home/ryuu/my-homeserver/run.sh";
$use_venv = $project['use_venv'];
$folder = $project['folder_name'];
$script = $project['script_name'];

// Escape argumen untuk keamanan
$escaped_args = array_map('escapeshellarg', $user_args);
$cmd = "bash $run_sh $use_venv $folder $script " . implode(' ', $escaped_args) . " 2>&1";

// 3. Eksekusi dan Stream Output
$descriptorspec = [
    0 => ["pipe", "r"], // stdin
    1 => ["pipe", "w"], // stdout
    2 => ["pipe", "w"]  // stderr
];

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    while ($line = fgets($pipes[1])) {
        echo "data: " . nl2br(htmlspecialchars($line)) . "\n\n";
        @ob_flush();
        flush();
    }
    fclose($pipes[1]);
    proc_close($process);
}
echo "data: [PROSES SELESAI]\n\n";
?>
