<?php
/**
 * File: views/admin/dashboard.php
 * Deskripsi: Admin Command Center Ultra-Futuristic sethmacy.hub
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: login"); exit(); }

$trans = new TransController($db);
$products = $trans->getProducts('', 100, 0); 

$status = $_GET['status'] ?? '';
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYSTEM COMMAND | sethmacy.hub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Plus+Jakarta+Sans:wght@300;400;700;800&display=swap');

        :root {
            --neon-blue: #00d2ff;
            --neon-purple: #ae00ff;
            --neon-green: #39ff14;
            --cyber-dark: #0a0a0f;
            --text-light: #e0e0e0;
        }

        body { background-color: var(--cyber-dark); color: var(--text-light); font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        #particles-js { position: fixed; width: 100%; height: 100%; z-index: -2; top: 0; left: 0; }

        body::before {
            content: ''; position: fixed; width: 600px; height: 600px;
            background: radial-gradient(circle, var(--neon-purple) 0%, transparent 70%);
            filter: blur(150px); border-radius: 50%; z-index: -1;
            bottom: -200px; left: -200px; opacity: 0.2;
            animation: moveGlow 15s infinite alternate ease-in-out;
        }

        @keyframes moveGlow { 0% { transform: translate(0, 0); } 100% { transform: translate(200px, -100px); } }

        .navbar {
            background: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 210, 255, 0.3);
            padding: 15px 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 20px;
            transition: 0.4s;
            backdrop-filter: blur(10px);
        }
        .stat-card:hover { border-color: var(--neon-blue); box-shadow: 0 0 20px rgba(0, 210, 255, 0.2); }

        .admin-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 210, 255, 0.2);
            border-radius: 35px;
            padding: 35px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            margin-bottom: 30px;
        }

        .terminal-header {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: var(--neon-blue);
            text-shadow: 0 0 10px var(--neon-blue);
            letter-spacing: 2px;
            border-left: 4px solid var(--neon-purple);
            padding-left: 15px;
            margin-bottom: 30px;
        }

        .table-container { border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.05); }
        .cyber-table { margin-bottom: 0; color: #fff; font-family: 'Share Tech Mono', monospace; }
        .cyber-table thead { background: rgba(0, 210, 255, 0.15); color: var(--neon-blue); }
        .cyber-table tbody tr { transition: 0.3s; background: rgba(255,255,255,0.01); }
        .cyber-table tbody tr:hover { background: rgba(0, 210, 255, 0.05); }

        .img-preview-sm { 
            width: 55px; height: 55px; object-fit: cover; 
            border-radius: 12px; border: 2px solid var(--neon-blue);
        }

        .cyber-input {
            background: rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(0, 210, 255, 0.2) !important;
            color: #fff !important;
            border-radius: 15px !important;
            padding: 12px 18px !important;
            font-family: 'Share Tech Mono', monospace;
        }
        .cyber-input:focus { border-color: var(--neon-blue) !important; box-shadow: 0 0 15px var(--neon-blue) !important; }

        .btn-uplink {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
            color: #fff; font-family: 'Orbitron', sans-serif; font-weight: 800;
            border: none; border-radius: 15px; padding: 15px;
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.3);
            transition: 0.4s; text-transform: uppercase;
        }
        .btn-uplink:hover { transform: translateY(-3px); box-shadow: 0 0 40px var(--neon-blue); color: #fff; }

        .status-online { color: var(--neon-green); font-size: 0.75rem; font-weight: bold; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

        .btn-action { font-size: 1.2rem; transition: 0.3s; padding: 5px 10px; border-radius: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--text-light); }
        .btn-edit:hover { color: var(--neon-blue); border-color: var(--neon-blue); box-shadow: 0 0 10px var(--neon-blue); }
        .btn-erase:hover { color: #ff4757; border-color: #ff4757; box-shadow: 0 0 10px #ff4757; }

        /* Modal Styling */
        .modal-content { background: rgba(5, 5, 10, 0.95); border: 1px solid var(--neon-blue); backdrop-filter: blur(20px); border-radius: 30px; }
        .modal-header { border-bottom: 1px solid rgba(0, 210, 255, 0.2); }
    </style>
</head>
<body>

<div id="particles-js"></div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" style="font-family: 'Orbitron';" href="#">
            <i class="bi bi-cpu-fill me-2"></i>SYSTEM<span class="text-info">_COMMAND</span>
        </a>
        <div class="ms-auto">
            <span class="text-secondary small me-3 d-none d-md-inline">ROOT_ACCESS: ACTIVE</span>
            <a href="logout" class="btn btn-outline-danger btn-sm rounded-pill px-4 cyber-sfx-trigger">DISCONNECT</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center gap-3" data-tilt>
                <div class="fs-1 text-info"><i class="bi bi-box-seam"></i></div>
                <div>
                    <small class="text-secondary d-block">TOTAL_HARDWARE</small>
                    <span class="fs-3 fw-bold"><?= count($products) ?> ITEMS</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center gap-3" data-tilt>
                <div class="fs-1 text-purple" style="color: var(--neon-purple);"><i class="bi bi-shield-check"></i></div>
                <div>
                    <small class="text-secondary d-block">SYSTEM_STATUS</small>
                    <span class="fs-3 fw-bold text-success">OPTIMAL</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center gap-3" data-tilt>
                <div class="fs-1 text-info"><i class="bi bi-activity"></i></div>
                <div>
                    <small class="text-secondary d-block">SERVER_LOAD</small>
                    <span class="fs-3 fw-bold">12ms LATENCY</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="admin-card">
                <h4 class="terminal-header">UPLINK_NEW_DATA</h4>
                <form action="index.php?url=add_product" method="POST">
                    <div class="mb-4">
                        <label class="form-label small text-info ms-2">DEVICE_NAME</label>
                        <input type="text" name="name" class="form-control cyber-input" placeholder="e.g. Neural Link v2" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-info ms-2">CREDIT_VAL (IDR)</label>
                        <input type="number" name="price" class="form-control cyber-input" placeholder="Price amount" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-info ms-2">VISUAL_UPLINK (URL)</label>
                        <input type="url" name="image" class="form-control cyber-input" placeholder="https://source.com/img.png">
                    </div>
                    <button type="submit" class="btn btn-uplink w-100 mt-2 cyber-sfx-trigger">EXECUTE_UPLOAD</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card">
                <h4 class="terminal-header">INVENTORY_MONITOR</h4>
                <div class="table-container table-responsive">
                    <table class="table cyber-table align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>VISUAL</th>
                                <th>HARDWARE</th>
                                <th>CREDITS</th>
                                <th class="text-center">OPS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $p): ?>
                            <tr>
                                <td class="ps-4 text-secondary">#<?= $p['id'] ?></td>
                                <td><img src="<?= $p['image'] ?>" class="img-preview-sm" onerror="this.src='https://via.placeholder.com/60/0a0a0f/00d2ff?text=X'"></td>
                                <td class="fw-bold"><?= $p['name'] ?></td>
                                <td class="text-info">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn-action btn-edit cyber-sfx-trigger" 
                                                onclick='openEditModal(<?= json_encode($p) ?>)'>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="index.php?url=delete_product&id=<?= $p['id'] ?>" 
                                           class="btn-action btn-erase cyber-sfx-trigger" 
                                           onclick="return confirm('INITIATE PERMANENT DATA ERASURE?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white">
            <div class="modal-header border-info border-opacity-25">
                <h5 class="modal-title font-orbitron text-info" style="font-family: 'Orbitron';"><i class="bi bi-pencil-square me-2"></i>RECONFIGURE_DATA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?url=edit_product" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-4">
                        <label class="form-label small text-info">HARDWARE_NAME</label>
                        <input type="text" name="name" id="edit-name" class="form-control cyber-input" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-info">CREDIT_VALUE</label>
                        <input type="number" name="price" id="edit-price" class="form-control cyber-input" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-info">VISUAL_PATH</label>
                        <input type="url" name="image" id="edit-image" class="form-control cyber-input">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-uplink w-100 cyber-sfx-trigger">UPDATE_DATABASE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Logic to Populate and Show Edit Modal
function openEditModal(product) {
    document.getElementById('edit-id').value = product.id;
    document.getElementById('edit-name').value = product.name;
    document.getElementById('edit-price').value = product.price;
    document.getElementById('edit-image').value = product.image;
    
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
    playCyberSound('click');
}

// --- AUDIO ENGINE ---
const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
function playCyberSound(type) {
    if (audioCtx.state === 'suspended') audioCtx.resume();
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.connect(gain); gain.connect(audioCtx.destination);
    if (type === 'click') {
        osc.type = 'sine'; osc.frequency.setValueAtTime(1200, audioCtx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(40, audioCtx.currentTime + 0.1);
        gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
        osc.start(); osc.stop(audioCtx.currentTime + 0.1);
    }
}

document.querySelectorAll('.cyber-sfx-trigger').forEach(btn => {
    btn.addEventListener('click', () => playCyberSound('click'));
});

// Notifications
<?php if($status == 'success'): ?>
    Swal.fire({ icon: 'success', title: 'DATA UPLINK SUCCESSFUL', background: '#0a0a0f', color: '#fff', confirmButtonColor: '#00d2ff' });
<?php elseif($status == 'updated'): ?>
    Swal.fire({ icon: 'success', title: 'RECONFIGURATION COMPLETE', background: '#0a0a0f', color: '#fff', confirmButtonColor: '#39ff14' });
<?php elseif($msg == 'deleted'): ?>
    Swal.fire({ icon: 'warning', title: 'DATA ERASED FROM MAINFRAME', background: '#0a0a0f', color: '#fff', confirmButtonColor: '#ff4757' });
<?php endif; ?>

// Particles Initialization
particlesJS('particles-js', {
    "particles": {
        "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
        "color": { "value": ["#00d2ff", "#ae00ff"] },
        "shape": { "type": "circle" },
        "opacity": { "value": 0.2 },
        "size": { "value": 2 },
        "line_linked": { "enable": true, "distance": 150, "color": "#00d2ff", "opacity": 0.1, "width": 1 },
        "move": { "enable": true, "speed": 1 }
    }
});

VanillaTilt.init(document.querySelectorAll(".stat-card, .admin-card"), {
    max: 5,
    speed: 400,
    glare: true,
    "max-glare": 0.2
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>