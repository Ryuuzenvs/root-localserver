<?php
header('Content-Type: application/json');

function getCpuUsage() {
    $stat1 = file_get_contents('/proc/stat');
    sleep(1); 
    $stat2 = file_get_contents('/proc/stat');

    preg_match('/cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $stat1, $info1);
    preg_match('/cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $stat2, $info2);

    $total1 = array_sum(array_slice($info1, 1));
    $idle1 = $info1[4];
    $total2 = array_sum(array_slice($info2, 1));
    $idle2 = $info2[4];

    $diff_total = $total2 - $total1;
    $diff_idle = $idle2 - $idle1;

    return ($diff_total == 0) ? 0 : round((($diff_total - $diff_idle) / $diff_total) * 100, 1);
}

// Total Processes
$process_count = shell_exec("ls /proc | grep -P '^\d+$' | wc -l");

// CPU Temp
$thermal = shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
$cpu_temp = round($thermal / 1000, 1);

// RAM Usage
$free = shell_exec("free -m");
$lines = explode("\n", $free);
$mem = preg_split('/\s+/', $lines[1]);
$mem_total = $mem[1];
$mem_used = $mem[2];
$mem_percent = round(($mem_used / $mem_total) * 100, 1);

// Battery
$battery = "N/A";
if (file_exists("/sys/class/power_supply/BAT0/capacity")) {
    $battery = trim(file_get_contents("/sys/class/power_supply/BAT0/capacity")) . "%";
}

echo json_encode([
    'cpu_usage' => getCpuUsage() . "%",
    'cpu_temp' => $cpu_temp . "°C",
    'mem_usage' => $mem_percent . "%",
    'mem_detail' => $mem_used . " / " . $mem_total . " MB",
    'total_procs' => trim($process_count),
    'battery' => $battery,
    'uptime' => str_replace("up ", "", shell_exec("uptime -p"))
]);
