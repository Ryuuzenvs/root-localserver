<?php
session_start();
if (!isset($_SESSION['logged_in'])) die("Access Denied");

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// PERBAIKAN DI SINI:
// Pakai __DIR__ saja kalau file ini ada di root folder project.
// Kalau file ini di dalam folder 'system/', baru pakai dirname(__DIR__)
$target_dir = $_GET['path'] ?? __DIR__; 

$commit_msg = $_GET['msg'] ?? 'Update via Web Dashboard ' . date('Y-m-d H:i:s');
//$target_dir = realpath($target_dir);

$path = escapeshellarg($target_dir);
// 1. Set Identitas (Wajib agar tidak error 'Author identity unknown')
shell_exec("cd $path && git config user.email 'ryuuzenvs@localserver.com'");
shell_exec("cd $path && git config user.name 'Ryuu Dashboard'");

// 2. Pastikan Safe Directory (Double check)
shell_exec("git config --global --add safe.directory $path");

echo "data: [📂] TARGET: $target_dir\n\n";

function send_log($msg) {
    echo "data: " . nl2br(htmlspecialchars($msg)) . "\n\n";
    @ob_flush();
    flush();
}

// Validasi path: Jangan biarkan nge-push folder sistem /var/www atau /
if ($target_dir == '/var/www' || $target_dir == '/') {
    send_log("[❌] ERROR: Bahaya! Tidak diizinkan push folder sistem.");
    echo "data: [PROSES SELESAI]\n\n";
    exit;
}

shell_exec("git config --global --add safe.directory " . escapeshellarg($target_dir));

if (!is_dir($target_dir . '/.git')) {
    send_log("[⚠️] Belum ada repo Git. Menjalankan 'git init'...");
    shell_exec("cd " . escapeshellarg($target_dir) . " && git init");
}

// Tambahkan config safe directory (Penting buat Linux local server)
shell_exec("git config --global --add safe.directory " . escapeshellarg($target_dir));

$git_ssh_bypass = "GIT_SSH_COMMAND='ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no'";

$cmd = "cd $path && " .
       "echo '--- [1/4] CHECKING STATUS ---' && git status 2>&1 && " .
       "echo '--- [2/4] ADDING FILES ---' && git add . 2>&1 && " .
       "echo '--- [3/4] COMMITTING ---' && (git commit -m " . escapeshellarg($commit_msg) . " 2>&1 || echo 'Nothing to commit') && " .
       "echo '--- [4/4] PUSHING TO REMOTE ---' && " .
       "$git_ssh_bypass git push origin main 2>&1";

$process = proc_open($cmd, [1 => ["pipe", "w"], 2 => ["pipe", "w"]], $pipes);

if (is_resource($process)) {
    while ($line = fgets($pipes[1])) {
        send_log($line);
    }
    fclose($pipes[1]);
    proc_close($process);
}

echo "data: [PROSES SELESAI]\n\n";
