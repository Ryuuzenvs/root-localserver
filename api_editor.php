<?php
session_start();
require('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['logged_in'])) {
    $action = $_POST['action'] ?? '';
    $root = $_SERVER['DOCUMENT_ROOT'];

    // 1. Rename
    if ($action === 'rename') {
        $old = realpath($root . $_POST['old_path']);
        $new = dirname($old) . DIRECTORY_SEPARATOR . $_POST['new_name'];
        if ($old && strpos($old, $root) === 0) rename($old, $new);
    }

    // 2. Create File (Touch)
    if ($action === 'touch') {
        $path = realpath($root . $_POST['current_dir']) . DIRECTORY_SEPARATOR . $_POST['file_name'];
        if (strpos($path, $root) === 0) file_put_contents($path, "");
    }

    // 3. Save Content
    if ($action === 'save_file') {
        $path = realpath($root . $_POST['filepath']);
        if ($path && strpos($path, $root) === 0) {
            file_put_contents($path, $_POST['content']);
            echo json_encode(['status' => 'success']);
            exit;
        }
    }
    
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
