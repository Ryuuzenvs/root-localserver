<?php 
session_start();
if(!isset($_SESSION['logged_in'])) die("Access Denied");

$file_relative = $_GET['file'] ?? ''; 
$file_path = realpath($_SERVER['DOCUMENT_ROOT'] . $file_relative);

// Safety check: Pastikan file ada dan masih di dalam root
if (!$file_path || !file_exists($file_path)) {
    die("File not found or access denied.");
}

$content = file_get_contents($file_path);
$ext = pathinfo($file_path, PATHINFO_EXTENSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neon Editor - <?= basename($file_relative) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #0a0a0a; color: #eee; overflow: hidden; }
        #editor-container { 
            height: calc(100vh - 60px); 
            width: 100%; 
            font-size: 14px;
        }
        .navbar-editor {
            background: rgba(20, 20, 20, 0.9);
            border-bottom: 1px solid #00d2ff;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.2);
        }
        .btn-neon-save {
            background: transparent;
            color: #00d2ff;
            border: 1px solid #00d2ff;
            box-shadow: inset 0 0 5px rgba(0, 210, 255, 0.2);
            transition: 0.3s;
        }
        .btn-neon-save:hover {
            background: #00d2ff;
            color: #000;
            box-shadow: 0 0 20px #00d2ff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-editor px-3 py-2 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm border-0 text-white">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <div class="vr bg-secondary mx-2"></div>
        <span class="small fw-bold text-info">
            <i class="bi bi-filetype-<?= $ext ?>"></i> <?= $file_relative ?>
        </span>
    </div>

    <div class="d-flex gap-2">
        <button onclick="saveFile()" class="btn btn-neon-save btn-sm px-4 fw-bold">
            <i class="bi bi-cloud-arrow-up-fill"></i> SAVE
        </button>
    </div>
</nav>

<div id="editor-container"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script>
    var editor = ace.edit("editor-container");
    editor.setTheme("ace/theme/monokai");
    
    // Mapping extension ke mode Ace
    var modes = {
        'py': 'ace/mode/python',
        'php': 'ace/mode/php',
        'js': 'ace/mode/javascript',
        'html': 'ace/mode/html',
        'css': 'ace/mode/css',
        'json': 'ace/mode/json'
    };
    editor.session.setMode(modes["<?= $ext ?>"] || "ace/mode/text");
    
    // Konfigurasi tambahan biar makin enak ngetik di i3
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true,
        showPrintMargin: false,
        useSoftTabs: true,
        tabSize: 4
    });

    // Masukin konten dengan aman
    editor.setValue(`<?= addslashes($content) ?>`, -1);

    function saveFile() {
        const btn = document.querySelector('.btn-neon-save');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> saving...';
        btn.disabled = true;

        const content = editor.getValue();
        const formData = new URLSearchParams();
        formData.append('action', 'save_file');
        formData.append('filepath', '<?= urlencode($file_relative) ?>');
        formData.append('content', content);

        fetch('api_editor.php', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            btn.innerHTML = '<i class="bi bi-check-lg"></i> SUCCESS';
            btn.classList.replace('btn-neon-save', 'btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.replace('btn-success', 'btn-neon-save');
                btn.disabled = false;
            }, 2000);
        })
        .catch(err => {
            alert("Error: " + err);
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Shortcut CTRL + S biar berasa VS Code
    document.addEventListener('keydown', function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
            e.preventDefault();
            saveFile();
        }
    }, false);
</script>
</body>
</html>
