<?php
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$is_mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|.../i', $userAgent);

// Cek khusus jika kamu ingin mematikan di Linux (Desktop)
$is_linux_desktop = strpos($userAgent, 'Linux') !== false && !strpos($userAgent, 'Android');

$use_heavy_ui = $is_mobile && !$is_linux_desktop; 
?>
<?php if ($use_heavy_ui): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            background: #0f0c29;
            background: linear-gradient(to right, #24243e, #302b63, #0f0c29);
            overflow: hidden; /* Biar partikel ga scroll */
        }
        #canvas-bg { position: fixed; top: 0; left: 0; z-index: -1; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
            color: #fff;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1) !important;
            border: none;
            color: white !important;
            padding: 12px;
        }
        .btn-glow {
            background: linear-gradient(45deg, #00dbde, #fc00ff);
            border: none;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: 0 0 15px rgba(0, 219, 222, 0.5);
        }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 0 25px rgba(252, 0, 255, 0.7); }
    </style>
<?php endif; ?>

<div id="canvas-bg"></div> <div class="container d-flex align-items-center justify-content-center" style="height: 90vh;">
    <div class="card p-4 glass-card animate__animated animate__fadeInUp" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
             <h1 style="font-size: 3rem;">🔐</h1>
             <h3 class="fw-bold mt-2">Ryuu Server</h3>
             <small class="text-info">Advanced System Access</small>
        </div>

        <?php if(isset($error)): ?> 
            <div class="alert alert-danger border-0 bg-danger text-white animate__animated animate__shakeX">
                <?= $error ?>
            </div> 
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label opacity-75">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Identify yourself..." required>
            </div>
            <div class="mb-3">
                <label class="form-label opacity-75">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-glow w-100 py-2 mt-3">
                INITIALIZE LOGIN
            </button>
        </form>
    </div>
</div>

<?php if ($use_heavy_ui): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
    // Simple Three.js Particle Background
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('canvas-bg').appendChild(renderer.domElement);

    const geometry = new THREE.BufferGeometry();
    const vertices = [];
    for (let i = 0; i < 5000; i++) {
        vertices.push(THREE.MathUtils.randFloatSpread(2000), THREE.MathUtils.randFloatSpread(2000), THREE.MathUtils.randFloatSpread(2000));
    }
    geometry.setAttribute('position', new THREE.Float32BufferAttribute(vertices, 3));
    const particles = new THREE.Points(geometry, new THREE.PointsMaterial({ color: 0x00dbde, size: 2 }));
    scene.add(particles);
    camera.position.z = 1000;

    function animate() {
        requestAnimationFrame(animate);
        particles.rotation.x += 0.001;
        particles.rotation.y += 0.002;
        renderer.render(scene, camera);
    }
    animate();
</script>
<?php endif; ?>

