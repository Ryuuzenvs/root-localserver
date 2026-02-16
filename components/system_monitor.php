<div class="mb-5">
    <h4 class="mb-4 fw-bold"><i class="bi bi-cpu-fill me-2"></i>System Monitor</h4>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="label-text mb-1">CPU Usage</div>
                <div id="cpu_usage" class="stat-value">0%</div>
                <div class="progress mt-3" style="height: 6px;">
                    <div id="cpu_bar" class="progress-bar bg-primary" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <div class="label-text mb-1">CPU Temperature</div>
                <div id="cpu_temp" class="stat-value">--°C</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4">
                <div class="label-text mb-1">Memory Usage</div>
                <div id="mem_usage" class="stat-value">0%</div>
                <div id="mem_detail" class="text-muted small mt-1">0 / 0 MB</div>
                <div class="progress mt-3" style="height: 6px;">
                    <div id="mem_bar" class="progress-bar bg-primary" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <div class="label-text">Processes</div>
                <div id="total_procs" class="h4 mb-0 fw-bold">0</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="label-text">Battery</div>
                <div id="battery" class="h4 mb-0 fw-bold">--</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="label-text">Uptime</div>
                <div id="uptime" class="small fw-bold" style="margin-top:5px">Loading...</div>
            </div>
        </div>
    </div>
</div>
