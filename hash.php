<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Hash Generator - Ryuu Localhost</title>
    <link rel="stylesheet" href="../bs/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        code { word-break: break-all; color: #d63384; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <h3 class="text-center mb-4">🔐 Password Hasher</h3>
                
                <form method="POST" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="password" class="form-control" placeholder="Masukkan password yang ingin di-hash..." required autofocus>
                        <button type="submit" class="btn btn-primary">Generate Hash</button>
                    </div>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['password'])) {
                    $plain = $_POST['password'];
                    $hashed = password_hash($plain, PASSWORD_DEFAULT);
                    
                    echo '<div class="alert alert-success">';
                    echo '<strong>Plain Text:</strong> <span class="ms-2">' . htmlspecialchars($plain) . '</span><br>';
                    echo '</div>';
                    
                    echo '<div class="alert alert-dark">';
                    echo '<strong>Hashed Result:</strong><br>';
                    echo '<code id="hashResult" class="d-block mt-2 p-2 bg-light border">' . $hashed . '</code>';
                    echo '<button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyToClipboard()">Copy Hash</button>';
                    echo '</div>';
                }
                ?>
                
                <div class="text-muted small text-center">
                    <em>Algoritma: <strong>BCRYPT</strong> (Default PHP)</em>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const text = document.getElementById('hashResult').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Hash berhasil di-copy ke clipboard!');
    });
}
</script>

</body>
</html>
