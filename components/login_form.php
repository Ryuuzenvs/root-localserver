<div class="container d-flex align-items-center justify-content-center" style="height: 80vh;">
    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">🔐 Login Ryuu Server</h3>
        <?php if(isset($error)): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>
</div>
