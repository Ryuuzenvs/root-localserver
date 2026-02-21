<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // --- JS MONITOR SYSTEM ---
    function updateStats() {
        if (!document.getElementById('cpu_usage')) return; // Cek jika element ada
        fetch('/api.php').then(res => res.json()).then(data => {
            document.getElementById('cpu_usage').innerText = data.cpu_usage;
            document.getElementById('cpu_bar').style.width = data.cpu_usage;
            document.getElementById('cpu_temp').innerText = data.cpu_temp;
            document.getElementById('mem_usage').innerText = data.mem_usage;
            document.getElementById('mem_detail').innerText = data.mem_detail;
            document.getElementById('mem_bar').style.width = data.mem_usage;
            document.getElementById('total_procs').innerText = data.total_procs;
            document.getElementById('battery').innerText = data.battery;
            document.getElementById('uptime').innerText = data.uptime;
        }).catch(err => console.error("Error stats:", err));
    }
    if (document.getElementById('cpu_usage')) setInterval(updateStats, 2000);

    // --- JS TERMINAL EXECUTOR ---
    let currentProjectId = null;
const modal = new bootstrap.Modal(document.getElementById('terminalModal'));

document.querySelectorAll('.btn-run-project').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const inputs = JSON.parse(this.dataset.inputs);
        
        currentProjectId = id;
        document.getElementById('modalTitle').innerText = name;
        document.getElementById('terminalBody').innerHTML = "Siap dijalankan...";
        
        // Generate Input Form
        let inputHtml = '';
        inputs.forEach((label, index) => {
            inputHtml += `
                <div class="mb-2">
                    <label class="small">${label}</label>
                    <input type="text" class="form-control form-control-sm py-input" data-index="${index}" placeholder="Masukkan ${label}...">
                </div>`;
        });
        document.getElementById('inputSection').innerHTML = inputHtml || '<p class="text-muted m-0">Tanpa input tambahan.</p>';
        
        modal.show();
    });
});

document.getElementById('btnStartExecute').addEventListener('click', function() {
    const inputs = document.querySelectorAll('.py-input');
    let params = '';
    inputs.forEach(inp => {
        params += `&args[]=${encodeURIComponent(inp.value)}`;
    });

    const terminal = document.getElementById('terminalBody');
    terminal.innerHTML = "[MENGHUBUNGKAN KE SERVER...]\n<br>";

    // Server-Sent Events (SSE) untuk output real-time
    const ev = new EventSource(`executor.php?id=${currentProjectId}${params}`);
    
    ev.onmessage = function(e) {
        if (e.data.includes("[PROSES SELESAI]")) {
            ev.close();
            terminal.innerHTML += "<br><span class='text-white'>--- SELESAI ---</span>";
        } else {
            terminal.innerHTML += e.data + "<br>";
            terminal.scrollTop = terminal.scrollHeight; // Auto-scroll ke bawah
        }
    };

    ev.onerror = function() {
        ev.close();
        terminal.innerHTML += "<br><span class='text-danger'>[KONEKSI TERPUTUS]</span>";
    };
});
    </script>
</body>
</html>
