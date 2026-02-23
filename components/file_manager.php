
<?php
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
    <div class="col">
        <a href="<?= $f['link'] ?>" class="item-card text-decoration-none d-block text-center <?= $mewah ? 'animate__animated animate__fadeIn' : '' ?>">
            <div class="folder-wrapper position-relative">
                <i class="bi bi-folder-fill text-warning" style="font-size: 3.5rem;"></i>
            </div>
            <div class="item-name fw-bold"><?= $f['name'] ?></div>
            <div class="badge rounded-pill bg-warning text-dark px-2" style="font-size: 0.6rem; opacity: 0.8;">Directory</div>
        </a>
    </div>
    <?php endforeach; ?>
    
    <?php foreach ($files_list as $f): ?>
    <div class="col">
        <a href="<?= $f['link'] ?>" class="item-card text-decoration-none d-block text-center">
            <div class="file-wrapper">
                <i class="bi bi-file-earmark-code-fill text-info" style="font-size: 3.5rem; opacity: 0.8;"></i>
            </div>
            <div class="item-name"><?= $f['name'] ?></div>
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
<div class="modal fade" id="terminalModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 10000;">
        <div class="modal-content bg-dark text-white border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-info" id="modalTitle">
                    <i class="bi bi-terminal me-2"></i>Terminal Output
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: relative; z-index: 10001;"></button>
            </div>
            <div class="modal-body" style="position: relative; z-index: 10001;">
                <div id="inputSection" class="mb-3 p-3 border border-secondary rounded bg-white bg-opacity-10">
                    </div>
                <div id="terminalBody" class="p-3 small border border-secondary rounded" style="height: 300px; background: #000; overflow-y: auto;">
                    Menunggu perintah...
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" id="btnStartExecute" class="btn btn-info w-100 fw-bold">JALANKAN SCRIPT</button>
            </div>
        </div>
    </div>
</div>
