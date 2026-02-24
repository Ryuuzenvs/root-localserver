
<?php
// --- CRUD LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['logged_in'])) {
    // 1. Buat Folder Baru
    if (isset($_POST['action']) && $_POST['action'] === 'mkdir') {
        $new_folder = $target_dir . DIRECTORY_SEPARATOR . $_POST['folder_name'];
        if (!file_exists($new_folder)) mkdir($new_folder, 0775);
    }
    
    // 2. Hapus File/Folder
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $target = $target_dir . DIRECTORY_SEPARATOR . $_POST['item_name'];
        if (is_dir($target)) rmdir($target); // Hati-hati: rmdir hanya kerja jika folder kosong
        else unlink($target);
    }

    // Refresh halaman agar list terupdate
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
// --- LOGIC FILE EXPLORER ---
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$root_path = realpath($_SERVER['DOCUMENT_ROOT']);
$target_dir = realpath($root_path . $uri);

// Security check agar tidak bisa akses luar root
if (!$target_dir || strpos($target_dir, $root_path) !== 0) {
    $target_dir = $root_path;
}

$files = @scandir($target_dir);
$ignore = ['.', '..', 'index.php', 'api.php', 'bootstrap.min.css', '.htaccess', 'layouts', 'components'];
$folders_list = [];
$files_list = [];

if ($files) {
    foreach ($files as $file) {
        if (in_array($file, $ignore)) continue;
        $full_path = $target_dir . DIRECTORY_SEPARATOR . $file;
        $clean_uri = rtrim($uri, '/');
        $web_link = $clean_uri . '/' . $file;

        if (is_dir($full_path)) {
            $folders_list[] = ['name' => $file, 'link' => $web_link];
        } else {
            $files_list[] = [
                'name' => $file, 
                'link' => $web_link, 
                'size' => round(@filesize($full_path)/1024, 1)
            ];
        }
    }
}

// --- LOGIC PYTHON PROJECTS ---
$projects = $pdo->query("SELECT * FROM py_projects")->fetchAll();
?>
<div class="mb-5">
    <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 1px;">🚀 Python Automation</small>
    <div class="row g-3 mt-1">
        <?php foreach ($projects as $proj): ?>
        <div class="col-md-3">
            <button class="btn btn-white border w-100 text-start p-3 shadow-sm btn-run-project" 
                    data-id="<?= $proj['id'] ?>" 
                    data-name="<?= $proj['name'] ?>"
                    data-inputs='<?= $proj['inputs'] ?>'>
                <i class="bi bi-play-circle-fill text-success me-2"></i>
                <span class="fw-bold"><?= $proj['name'] ?></span>
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="d-flex justify-content-between align-items-end mb-3 mt-4">
    <div>
        <h5 class="text-white mb-0" style="text-shadow: 0 0 10px #00d2ff;">File Explorer</h5>
        <small class="text-muted"><?= count($folders_list) ?> Folders | <?= count($files_list) ?> Files</small>
    </div>
    <div class="d-flex gap-2">
        <form method="POST" class="d-flex gap-2 align-items-center bg-dark p-1 rounded-3 border border-secondary">
            <input type="hidden" name="action" value="mkdir">
            <input type="text" name="folder_name" class="form-control form-control-sm bg-transparent text-white border-0" placeholder="New Folder..." required style="width: 150px;">
            <button type="submit" class="btn btn-sm btn-info shadow-sm">
                <i class="bi bi-folder-plus"></i>
            </button>
        </form>
    </div>
</div>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white p-2 border rounded shadow-sm">
        <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i> Root</a></li>
        <?php 
        $parts = array_filter(explode('/', $uri));
        $path_accum = '';
        foreach ($parts as $part): 
            $path_accum .= '/' . $part;
        ?>
            <li class="breadcrumb-item"><a href="<?= $path_accum ?>"><?= $part ?></a></li>
        <?php endforeach; ?>
    </ol>
</nav>

<div class="row row-cols-2 row-cols-md-5 g-4">
    <?php if ($uri != '/' && $uri != ''): ?>
    <div class="col">
        <a href=".." class="item-card border-danger">
            <i class="bi bi-arrow-left-circle-fill icon-lg text-danger" style="font-size: 2rem;"></i>
            <div class="item-name">.. Kembali</div>
        </a>
    </div>
    <?php endif; ?>

   <?php foreach ($folders_list as $f): ?>
<div class="col position-relative group-item">
    <form method="POST" class="position-absolute" style="top: 5px; right: 20px; z-index: 10;" onsubmit="return confirm('Hapus folder ini?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="item_name" value="<?= $f['name'] ?>">
        <button type="submit" class="btn btn-link text-danger p-0 m-0" style="text-decoration: none; opacity: 0.6;">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    </form>

    <a href="<?= $f['link'] ?>" class="item-card text-decoration-none d-block text-center <?= $mewah ? 'animate__animated animate__fadeIn' : '' ?>">
        <div class="folder-wrapper position-relative">
            <i class="bi bi-folder-fill text-warning" style="font-size: 3.5rem;"></i>
        </div>
        <div class="item-name fw-bold text-white"><?= $f['name'] ?></div>
        <div class="badge rounded-pill bg-warning text-dark px-2" style="font-size: 0.6rem; opacity: 0.8;">Directory</div>
    </a>
</div>
<?php endforeach; ?>
    
    <?php foreach ($files_list as $f): ?>
<div class="col position-relative group-item">
    <form method="POST" class="position-absolute" style="top: 5px; right: 20px; z-index: 10;" onsubmit="return confirm('Hapus file ini?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="item_name" value="<?= $f['name'] ?>">
        <button type="submit" class="btn btn-link text-danger p-0 m-0" style="text-decoration: none; opacity: 0.6;">
            <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i>
        </button>
    </form>

    <a href="<?= $f['link'] ?>" class="item-card text-decoration-none d-block text-center">
        <div class="file-wrapper">
            <i class="bi bi-file-earmark-code-fill text-info" style="font-size: 3.5rem; opacity: 0.8;"></i>
        </div>
        <div class="item-name text-light"><?= $f['name'] ?></div>
        <div class="text-muted" style="font-size: 0.65rem;"><?= $f['size'] ?> KB</div>
    </a>
</div>
<?php endforeach; ?>
</div>


<?php if ($mewah): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>
<script>
    // Efek background kabut yang berat banget tapi smooth di G99
    VANTA.FOG({
      el: "body",
      mouseControls: true,
      touchControls: true,
      gyroControls: true, // Pakai Gyro HP!
      minHeight: 200.00,
      minWidth: 200.00,
     highlightColor: 0x00d2ff, // Biru neon terang
  midtoneColor: 0x16213e,
  lowlightColor: 0x050505,
  baseColor: 0x050505,
  blurFactor: 0.9, // Makin blur makin mewah
  speed: 1.5
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
<script>
    VanillaTilt.init(document.querySelectorAll(".card"), {
        max: 10,
        speed: 400,
        glare: true,
        "max-glare": 0.2,
    });
</script>
<?php endif; ?>
<div id="simpleInputOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:99999; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#111; border:1px solid #00d2ff; padding:20px; border-radius:10px; width:100%; max-width:400px; box-shadow:0 0 20px rgba(0,210,255,0.3);">
        <h6 id="ovTitle" style="color:#00d2ff; margin-bottom:15px; font-weight:bold;">PARAMETER SETUP</h6>
        <div id="ovBody"></div>
        <div style="margin-top:20px; display:flex; gap:10px;">
            <button id="ovCancel" style="flex:1; background:#333; color:#fff; border:none; padding:10px; border-radius:5px;">CANCEL</button>
            <button id="ovConfirm" style="flex:2; background:#00d2ff; color:#000; border:none; padding:10px; border-radius:5px; font-weight:bold;">LAUNCH</button>
        </div>
    </div>
</div>
