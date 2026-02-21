<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryuu Local Server</title>
    <link href="/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; }
        .card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: #2c3e50; }
        .item-card { background: white; border-radius: 10px; padding: 20px; text-align: center; transition: 0.3s; cursor: pointer; text-decoration: none; display: block; height: 100%; border: 1px solid #e0e0e0; }
        .item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .navbar-custom { background: white; border-bottom: 1px solid #e0e0e0; padding: 10px 20px; }
        #terminalBody { background: #000; color: #0f0; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['logged_in'])): ?>
<div class="navbar-custom d-flex justify-content-between align-items-center mb-4">
    <span class="fw-bold">📁 Storage Explorer</span>
    <div class="d-flex align-items-center">
        <span class="me-3 small text-muted">User: <?= $_SESSION['username'] ?></span>
        <a href="?logout=1" class="btn btn-sm btn-outline-danger">Logout</a>
    </div>
</div>
<?php endif; ?>
