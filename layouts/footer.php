<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if ($mewah): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
    <script>
        // Efek kabut hanya jika di HP
        VANTA.FOG({
            el: "#canvas-bg",
            mouseControls: false,
            touchControls: true,
            gyroControls: true,
            minHeight: 200.00,
            minWidth: 200.00,
            highlightColor: 0x00d2ff,
            midtoneColor: 0x16213e,
            lowlightColor: 0x050505,
            baseColor: 0x050505,
            speed: 1.5
        });

        VanillaTilt.init(document.querySelectorAll(".card, .item-card"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.2,
        });
    </script>
    <?php endif; ?>

    <script>
// Paksa fokus ke input saat modal terbuka
const myModalEl = document.getElementById('terminalModal');
myModalEl.addEventListener('shown.bs.modal', function () {
    const firstInput = myModalEl.querySelector('input');
    if (firstInput) firstInput.focus();
});

// Fix untuk memastikan klik tidak terhalang
document.querySelectorAll('.btn-run-project').forEach(btn => {
    btn.onclick = () => {
        // Reset scroll body agar tidak ngebug di mobile
        document.body.style.overflow = 'hidden'; 
    };
});

myModalEl.addEventListener('hidden.bs.modal', function () {
    document.body.style.overflow = 'auto';
});
    // --- JS MONITOR SYSTEM ---
    function updateStats() {
        const cpuEl = document.getElementById('cpu_usage');
        if (!cpuEl) return;
        fetch('/api.php').then(res => res.json()).then(data => {
            cpuEl.innerText = data.cpu_usage;
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
    if (document.getElementById('cpu_usage')) {
        updateStats();
        setInterval(updateStats, 3000);
    }

    // --- JS TERMINAL EXECUTOR ---
    let currentProjectId = null;
    const terminalModalEl = document.getElementById('terminalModal');
    const modal = new bootstrap.Modal(terminalModalEl);

    document.querySelectorAll('.btn-run-project').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const inputs = JSON.parse(this.dataset.inputs);
        const isMobile = <?= json_encode($mewah) ?>; // Ambil variabel PHP ke JS

        // Jika ada input yang harus diisi, kita tetep butuh prompt
        let args = [];
        if (inputs.length > 0) {
            for (let label of inputs) {
                let val = prompt(`Masukkan ${label}:`);
                if (val === null) return; // Batal kalau user klik cancel
                args.push(val);
            }
        }

        if (isMobile) {
            // MODE MOBILE: Buka Tab Baru
            let query = `id=${id}&name=${encodeURIComponent(name)}`;
            args.forEach(a => query += `&args[]=${encodeURIComponent(a)}`);
            window.open(`terminal.php?${query}`, '_blank');
        } else {
            // MODE DESKTOP: Tetap pake Modal (Logika lama kamu)
            currentProjectId = id;
            document.getElementById('modalTitle').innerText = name;
            // ... panggil seperti biasa ...
             modal.show()
        }
    });
});

    document.getElementById('btnStartExecute').addEventListener('click', function() {
        const inputs = document.querySelectorAll('.py-input');
        let params = '';
        inputs.forEach(inp => params += `&args[]=${encodeURIComponent(inp.value)}`);

        const terminal = document.getElementById('terminalBody');
        terminal.innerHTML = "<span class='text-info'>[CONNECTING TO RYUU ENGINE...]</span><br>";

        const ev = new EventSource(`executor.php?id=${currentProjectId}${params}`);
        ev.onmessage = function(e) {
            if (e.data.includes("[PROSES SELESAI]")) {
                ev.close();
                terminal.innerHTML += "<br><span class='text-success'>--- EXECUTION FINISHED ---</span>";
            } else {
                terminal.innerHTML += e.data + "<br>";
                terminal.scrollTop = terminal.scrollHeight;
            }
        };
        ev.onerror = () => { ev.close(); terminal.innerHTML += "<br><span class='text-danger'>[IO ERROR]</span>"; };
    });
    </script>

</body>
</html>
