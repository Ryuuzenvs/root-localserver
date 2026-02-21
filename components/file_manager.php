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
        <a href="<?= $f['link'] ?>" class="item-card">
            <i class="bi bi-folder-fill icon-lg text-warning" style="font-size: 3rem;"></i>
            <div class="item-name"><?= $f['name'] ?></div>
        </a>
    </div>
    <?php endforeach; ?>

    <?php foreach ($files_list as $f): ?>
    <div class="col">
        <a href="<?= $f['link'] ?>" class="item-card">
            <i class="bi bi-file-earmark-text-fill icon-lg text-secondary" style="font-size: 3rem;"></i>
            <div class="item-name"><?= $f['name'] ?></div>
            <div class="text-muted small" style="font-size: 0.7rem;"><?= $f['size'] ?> KB</div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="terminalModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalTitle">Terminal Output</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="inputSection" class="mb-3 p-3 border border-secondary rounded bg-secondary bg-opacity-10"></div>
                <div id="terminalBody" class="p-3 small" style="height: 300px; overflow-y: auto;">
                    Menunggu perintah...
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" id="btnStartExecute" class="btn btn-success">Jalankan</button>
            </div>
        </div>
    </div>
</div>
