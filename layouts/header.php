<?php
$ua = $_SERVER['HTTP_USER_AGENT'];
$is_mobile = preg_match('/Mobile|Android|iPhone/i', $ua);
$is_linux_desktop = (strpos($ua, 'Linux') !== false && !strpos($ua, 'Android'));
$mewah = ($is_mobile && !$is_linux_desktop); 
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $mewah ? 'dark' : 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryuu Local Server</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="fasfa/css/all.min.css"/>
    <link rel="stylesheet" href="../fasfa/css/all.min.css"/>
    <link rel="stylesheet" href="../../fasfa/css/all.min.css"/>
    <link rel="stylesheet" href="bs/bootstrap.min.css"/>
    <link rel="stylesheet" href="../bs/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../bs/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="bs/bootstrap-icons-1.13.1/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="../bs/bootstrap-icons-1.13.1/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="../../bs/bootstrap-icons-1.13.1/bootstrap-icons.min.css"/>
<style>
/* Custom Pure Black Theme Bootstrap 5.3 */
[data-bs-theme="dark"] {
    --bs-body-bg: #050505;
    --bs-body-color: #e0e0e0;
    --bs-tertiary-bg: #111111;
}

[data-bs-theme="dark"] .card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}

[data-bs-theme="dark"] .navbar-custom {
    background: rgba(0, 0, 0, 0.8) !important;
    border-bottom: 1px solid #333;
}

/* Biar icon folder tetep nyala di tema gelap */
[data-bs-theme="dark"] .bi-folder-fill {
    color: #ffca28 !important;
}
    .item-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 15px;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }
    .item-card:hover {
        background: rgba(0, 210, 255, 0.1);
        border-color: #00d2ff;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }
    .item-name {
        font-size: 0.85rem;
        margin-top: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* Sembunyikan tombol hapus kecuali saat di hover */
    .group-item form { visibility: hidden; opacity: 0; transition: 0.2s; }
    .group-item:hover form { visibility: visible; opacity: 1; }
</style>
    <style>
/* Pastikan Modal berada di kasta tertinggi */
.modal {
z-index: 100051 !important;
}

.modal-backdrop {
z-index: 100050 !important;
}

canvas.vanta-canvas {
        pointer-events: none !important;
    }

/* Fix agar canvas background benar-benar di bawah dan tidak menangkap klik */
#canvas-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1 !important; /* Wajib minus */
    pointer-events: none;   /* Supaya klik 'tembus' ke elemen di bawahnya */
}

/* Khusus mewah mode, pastikan body tidak memblokir pointer */
.mewah-mode {
    isolation: isolate;
}
        /* BASE STYLE (Standard Laptop) */
        body { background: #f4f7f6; transition: 0.5s; position: relative; }
        .navbar-custom { background: white; border-bottom: 1px solid #e0e0e0; padding: 10px 20px; z-index: 1000; position: relative; }
        .card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: white; color: #2c3e50; }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: #2c3e50; }
        #canvas-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none; }
        
        /* FOLDER CARD STANDARD */
        .item-card { background: white; border-radius: 10px; padding: 20px; text-align: center; transition: 0.3s; cursor: pointer; text-decoration: none; display: block; border: 1px solid #e0e0e0; color: #2c3e50; }
        .item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        /* HIGH-END MODE (Mobile HP Only) */
        <?php if ($mewah): ?>
        body { background: #050505 !important; color: #ffffff; overflow-x: hidden; }
        .card { 
            background: rgba(255, 255, 255, 0.05) !important; 
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }
        .stat-value { 
            color: #ffffff !important; 
            text-shadow: 0 0 15px rgba(0, 210, 255, 0.8) !important; 
        }
        .label-text { color: rgba(255,255,255,0.7) !important; font-weight: bold; }
        .item-card { 
            background: rgba(255, 255, 255, 0.05) !important; 
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: white !important;
        }
        .item-name { color: white !important; }
        .navbar-custom { background: rgba(0,0,0,0.5) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); color: white !important; }
        .text-muted { color: rgba(255,255,255,0.5) !important; }
        <?php endif; ?>
        
        #terminalBody { background: #000; color: #0f0; border-radius: 5px; font-family: monospace; overflow-y: auto; }
    </style>
</head>
<body class="<?= $mewah ? 'mewah-mode' : '' ?>">
<div id="canvas-bg"></div>

<?php if (isset($_SESSION['logged_in'])): ?>
<div class="navbar-custom d-flex justify-content-between align-items-center mb-4">
    <span class="fw-bold"><i class="bi bi-hdd-network me-2"></i>Ryuu Server Explorer</span>
    <div class="d-flex align-items-center">
        <span class="me-3 small opacity-75">Dev: <?= $_SESSION['username'] ?></span>
<div class="d-flex align-items-center me-3">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="darkModeSwitch" style="cursor: pointer;">
        <label class="form-check-label small opacity-75 ms-1" for="darkModeSwitch">
            <i class="bi bi-moon-stars-fill"></i>
        </label>
    </div>
</div>
        <a href="?logout=1" class="btn btn-sm btn-outline-danger">Logout</a>
    </div>
</div>
<?php endif; ?>
