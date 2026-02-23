<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// --- GLOBAL VARIABLES ---
let currentProjData = null;

document.querySelectorAll('.btn-run-project').forEach(btn => {
    btn.onclick = function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        let inputs = JSON.parse(this.dataset.inputs || "[]");
        
        currentProjData = { id, name };

        if (inputs.length > 0) {
            let html = '';
            inputs.forEach((label, i) => {
                html += `
                <div style="margin-bottom:10px;">
                    <label style="color:#888; font-size:12px; display:block;">${label}</label>
                    <input type="text" class="ov-input" data-index="${i}" placeholder="Enter value..." 
                        style="width:100%; background:#222; border:1px solid #444; color:#fff; padding:8px; border-radius:4px; outline:none;">
                </div>`;
            });
            document.getElementById('ovBody').innerHTML = html;
            document.getElementById('ovTitle').innerText = name.toUpperCase();
            document.getElementById('simpleInputOverlay').style.display = 'flex';
            
            // Auto focus ke input pertama & aktifkan listener ENTER
            const firstInp = document.querySelector('.ov-input');
            if(firstInp) {
                firstInp.focus();
                // Listener Enter untuk semua input
                document.querySelectorAll('.ov-input').forEach(inp => {
                    inp.onkeypress = function(e) {
                        if (e.key === 'Enter') document.getElementById('ovConfirm').click();
                    };
                });
            }
        } else {
            launchTerminal(id, name, []);
        }
    };
});

// Tombol Confirm di Overlay
document.getElementById('ovConfirm').onclick = function() {
    const vals = Array.from(document.querySelectorAll('.ov-input')).map(i => i.value);
    document.getElementById('simpleInputOverlay').style.display = 'none';
    launchTerminal(currentProjData.id, currentProjData.name, vals);
};

// Tombol Cancel
document.getElementById('ovCancel').onclick = function() {
    document.getElementById('simpleInputOverlay').style.display = 'none';
};

function launchTerminal(id, name, args) {
    let query = `id=${id}&name=${encodeURIComponent(name)}`;
    args.forEach(a => query += `&args[]=${encodeURIComponent(a)}`);
    window.open(`terminal.php?${query}`, '_blank');
}
// 5. System Monitor (API Stats)
function updateStats() {
    const cpuEl = document.getElementById('cpu_usage');
    if (!cpuEl) return;
    
    fetch('/api.php')
        .then(res => res.json())
        .then(data => {
            cpuEl.innerText = data.cpu_usage;
            document.getElementById('cpu_bar').style.width = data.cpu_usage;
            document.getElementById('cpu_temp').innerText = data.cpu_temp;
            document.getElementById('mem_usage').innerText = data.mem_usage;
            document.getElementById('mem_detail').innerText = data.mem_detail;
            document.getElementById('mem_bar').style.width = data.mem_usage;
            document.getElementById('total_procs').innerText = data.total_procs;
            document.getElementById('battery').innerText = data.battery;
            document.getElementById('uptime').innerText = data.uptime;
        })
        .catch(err => console.log("Stats offline"));
}

if (document.getElementById('cpu_usage')) {
    updateStats();
    setInterval(updateStats, 3000);
}
</script>
</body>
</html>
