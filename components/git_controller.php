<div class="card bg-dark text-white p-3 border-secondary mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-git me-2 text-orange"></i> Git Controller</h5>
        <button onclick="runGitPush()" class="btn btn-outline-success btn-sm">
            <i class="bi bi-cloud-arrow-up"></i> Push Root Project
        </button>
    </div>
</div>

<script>
function runGitPush() {
    let pesan = prompt("Pesan Commit (Opsional):", "Auto update dari Dashboard");
    if (pesan !== null) {
        // Kita kirim type=git agar terminal.php tahu harus pakai git_executor.php
        let url = `terminal.php?type=git&msg=${encodeURIComponent(pesan)}`;
        window.open(url, 'GitTerminal', 'width=900,height=600');
    }
}
</script>
<style>
    .text-orange { color: #f34f29; } /* Warna khas Git */
</style>
