<?php
// terminal.php
$name = $_GET['name'] ?? 'Terminal';
$id = $_GET['id'] ?? '';
$args = $_GET['args'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Executing: <?= htmlspecialchars($name) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #000; color: #0f0; font-family: monospace; padding: 20px; }
        #output { white-space: pre-wrap; word-break: break-all; height: 80vh; overflow-y: auto; }
        .status { color: #555; border-bottom: 1px solid #222; margin-bottom: 10px; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="status">
        [⚙️] PROJECT: <?= htmlspecialchars($name) ?><br>
        [🚀] STATUS: Running...
    </div>
    <div id="output">Menghubungkan ke server...</div>
    
    <button onclick="self.close()" class="btn btn-outline-danger btn-sm mt-3 w-100">
    <i class="bi bi-x-circle me-1"></i> Exit Terminal
</button>

    <script>
        const output = document.getElementById('output');
        const params = new URLSearchParams(window.location.search);

        const executorFile = params.get('type') === 'git' ? 'git_executor.php' : 'executor.php';
        
        // Ambil ID dan semua args dari URL
        const ev = new EventSource(`${executorFile}?${params.toString()}`);
        
        ev.onmessage = function(e) {
            if (e.data.includes("[PROSES SELESAI]")) {
                ev.close();
                output.innerHTML += "<br><span style='color:white'>--- SELESAI ---</span>";
            } else {
                output.innerHTML += e.data + "\n";
                window.scrollTo(0, document.body.scrollHeight);
            }
        };
        ev.onerror = () => { ev.close(); output.innerHTML += "\n[KONEKSI TERPUTUS]"; };
    </script>
</body>
</html>
