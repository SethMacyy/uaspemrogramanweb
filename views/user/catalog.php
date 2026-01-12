<?php
/**
 * File: views/user/catalog.php
 * Deskripsi: Neo Terminal Catalog dengan Partikel, 3D Tilt, dan Cyber SFX
 */
if (!isset($_SESSION['role'])) { header("Location: login"); exit(); }
$trans = new TransController($db);
$search = $_GET['search'] ?? '';
$page = (int)($_GET['p'] ?? 1);
$limit = 8;
$offset = ($page - 1) * $limit;
$products = $trans->getProducts($search, $limit, $offset);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TERMINAL CATALOG | sethmacy.hub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Plus+Jakarta+Sans:wght@300;400;700;800&display=swap');

        :root {
            --neon-blue: #00d2ff;
            --neon-purple: #ae00ff;
            --cyber-dark: #0a0a0f;
            --text-light: #e0e0e0;
        }

        body {
            background-color: var(--cyber-dark);
            color: var(--text-light);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        #particles-js { position: fixed; width: 100%; height: 100%; z-index: -2; top: 0; left: 0; }

        body::before {
            content: ''; position: fixed; width: 500px; height: 500px;
            background: radial-gradient(circle, var(--neon-blue) 0%, var(--neon-purple) 40%, transparent 70%);
            filter: blur(180px); border-radius: 50%; z-index: -1;
            top: -100px; right: -100px; opacity: 0.4;
            animation: moveGlow 15s infinite alternate ease-in-out;
        }

        @keyframes moveGlow { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-300px, 400px) scale(1.3); } }

        #splash-loading {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95); z-index: 10000; display: none;
            flex-direction: column; align-items: center; justify-content: center;
            backdrop-filter: blur(20px);
        }
        .scan-line {
            width: 500px; height: 4px; background: var(--neon-blue);
            box-shadow: 0 0 30px var(--neon-blue), 0 0 50px var(--neon-purple);
            animation: scan 2s linear infinite;
        }
        @keyframes scan { 0%, 100% { transform: translateY(-150px); opacity: 0; } 50% { transform: translateY(150px); opacity: 1; } }

        .navbar { background: rgba(0, 0, 0, 0.4) !important; backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0, 210, 255, 0.2); font-family: 'Orbitron', sans-serif; padding: 15px 0; }
        .navbar-brand { font-weight: 900; letter-spacing: 2px; text-shadow: 0 0 10px var(--neon-blue); }

        .holographic-title {
            font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 3.5rem;
            text-shadow: 0 0 15px var(--neon-blue), 0 0 30px var(--neon-purple);
            animation: glowPulse 2s infinite alternate;
        }
        .subtitle-glitch { font-family: 'Share Tech Mono', monospace; color: var(--neon-blue); letter-spacing: 5px; animation: glitchEffect 10s infinite alternate; }

        @keyframes glowPulse { from { text-shadow: 0 0 10px var(--neon-blue), 0 0 20px var(--neon-purple); } to { text-shadow: 0 0 20px var(--neon-blue), 0 0 40px var(--neon-purple); } }

        .product-card {
            background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 35px;
            padding: 30px; transition: all 0.5s ease; transform-style: preserve-3d;
            position: relative; overflow: hidden;
        }
        .product-card:hover { border-color: var(--neon-blue); box-shadow: 0 0 40px rgba(0, 210, 255, 0.2), 0 0 60px rgba(174, 0, 255, 0.1); background: rgba(255, 255, 255, 0.06); }

        .product-img { width: 100%; height: 220px; object-fit: contain; filter: drop-shadow(0 0 15px var(--neon-blue)); transform: translateZ(60px); transition: 0.5s; }

        .btn-cyber {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
            color: #fff; font-family: 'Orbitron', sans-serif; font-weight: bold;
            border: none; border-radius: 15px; padding: 12px;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);
            transition: 0.4s; transform: translateZ(30px);
        }
        .btn-cyber:hover { background: #fff; color: #000; box-shadow: 0 0 30px #fff; transform: translateZ(40px) scale(1.05); }

        .float-btn { position: fixed; bottom: 40px; z-index: 1000; cursor: pointer; transition: 0.4s; }
        .cart-anchor { right: 40px; }
        .history-anchor { left: 40px; }
        .float-btn:hover { transform: scale(1.15) rotate(-5deg); }

        .cart-img-custom { width: 100px; filter: drop-shadow(0 0 20px var(--neon-blue)); animation: pulseCart 2s infinite; }
        @keyframes pulseCart { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        
        .history-icon-custom {
            width: 80px; height: 80px; background: rgba(0, 210, 255, 0.05);
            border: 1px solid var(--neon-blue); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--neon-blue); font-size: 2.2rem; backdrop-filter: blur(10px);
            box-shadow: 0 0 25px rgba(0, 210, 255, 0.2);
        }

        .cart-badge-neon {
            position: absolute; top: 0; right: 0; background: #ff4757; color: #fff;
            width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px; border: 3px solid var(--cyber-dark); box-shadow: 0 0 15px #ff4757;
        }

        .modal-content { background: rgba(5, 5, 10, 0.95) !important; backdrop-filter: blur(30px); border: 1px solid var(--neon-blue); border-radius: 40px; color: #fff; }
        .history-card { background: rgba(255,255,255,0.03); border-radius: 20px; padding: 15px; margin-bottom: 15px; border-left: 3px solid var(--neon-blue); }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div id="splash-loading">
    <div class="scan-line"></div>
    <h2 class="text-info mt-4 fw-bold" style="font-family: 'Orbitron', sans-serif; letter-spacing: 8px;">UPLOADING DATA...</h2>
</div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">SETHMACY<span class="text-info">.HUB</span></a>
        <div class="ms-auto d-flex align-items-center gap-4">
            <span class="small opacity-50 d-none d-md-block" style="font-family: 'Share Tech Mono';">ID: <?= $_SESSION['username'] ?></span>
            <a href="logout" class="btn btn-outline-danger btn-sm rounded-pill px-4 cyber-sfx-trigger">DISCONNECT</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="holographic-title mb-2">NEO TERMINAL</h1>
        <p class="subtitle-glitch">/// SELECTION INTERFACE V4.0 ///</p>
        
        <form action="" method="GET" class="mx-auto mt-4" style="max-width: 600px;">
            <input type="hidden" name="url" value="catalog">
            <div class="input-group">
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary rounded-start-pill ps-4" placeholder="Scan for device..." value="<?= $search ?>">
                <button class="btn btn-info rounded-end-pill px-4 cyber-sfx-trigger" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <?php foreach ($products as $p): 
            $imgSrc = !empty($p['image']) ? $p['image'] : "https://via.placeholder.com/400x400/020205/00d2ff?text=" . urlencode($p['name']);
        ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card text-center" data-tilt data-tilt-max="15">
                <img src="<?= $imgSrc ?>" class="product-img mb-4" onerror="this.src='https://via.placeholder.com/400x400/020205/00d2ff?text=No+Image'">
                <h5 class="fw-bold mb-1" style="font-family: 'Orbitron'; font-size: 1rem;"><?= $p['name'] ?></h5>
                <p class="text-info fw-bold fs-5 mb-4">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                <button class="btn-cyber w-100 cyber-sfx-trigger" onclick="addToCart(<?= $p['id'] ?>, '<?= $p['name'] ?>', <?= $p['price'] ?>)">
                    ADD TO BAG
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="float-btn history-anchor cyber-sfx-trigger" onclick="openHistory()">
    <div class="history-icon-custom"><i class="bi bi-clock-history"></i></div>
</div>

<div class="float-btn cart-anchor cyber-sfx-trigger" data-bs-toggle="modal" data-bs-target="#cartModal">
    <img src="https://cdn-icons-png.flaticon.com/512/4290/4290854.png" class="cart-img-custom">
    <span class="cart-badge-neon" id="cart-count">0</span>
</div>

<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-5">
                <h2 class="fw-bold mb-4 text-info" style="font-family: 'Orbitron';"><i class="bi bi-clock-history me-3"></i>ORDER HISTORY</h2>
                <div id="history-list" style="max-height: 400px; overflow-y: auto;"></div>
                <button class="btn btn-outline-info w-100 mt-4 rounded-pill cyber-sfx-trigger" data-bs-dismiss="modal">CLOSE TERMINAL</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-5">
                <h2 class="fw-bold mb-4" style="font-family: 'Orbitron';">YOUR BAG.</h2>
                <div id="cart-list" class="mb-4"></div>
                <div class="d-flex justify-content-between mb-4 fs-4 fw-bold">
                    <span>Total</span>
                    <span id="cart-total" class="text-info">Rp 0</span>
                </div>
                <button class="btn btn-cyber w-100 py-3 cyber-sfx-trigger" onclick="goToCheckout()">CHECKOUT NOW</button>
            </div>
        </div>
    </div>
</div>

<script>
// --- AUDIO ENGINE START ---
const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
function playCyberSound(type) {
    if (audioCtx.state === 'suspended') audioCtx.resume();
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.connect(gain);
    gain.connect(audioCtx.destination);

    if (type === 'click') {
        osc.type = 'sine';
        osc.frequency.setValueAtTime(1200, audioCtx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(40, audioCtx.currentTime + 0.1);
        gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
        osc.start(); osc.stop(audioCtx.currentTime + 0.1);
    } else if (type === 'success') {
        osc.type = 'square';
        osc.frequency.setValueAtTime(400, audioCtx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(1000, audioCtx.currentTime + 0.3);
        gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
        osc.start(); osc.stop(audioCtx.currentTime + 0.3);
    }
}
// Efek suara untuk semua tombol yang memiliki class cyber-sfx-trigger
document.querySelectorAll('.cyber-sfx-trigger').forEach(btn => {
    btn.addEventListener('click', () => playCyberSound('click'));
});
// --- AUDIO ENGINE END ---

let cart = JSON.parse(localStorage.getItem('sethmacy_cart')) || [];

function updateUI() {
    document.getElementById('cart-count').innerText = cart.length;
    const list = document.getElementById('cart-list');
    let total = 0; list.innerHTML = '';
    if(cart.length === 0) {
        list.innerHTML = '<p class="text-secondary text-center py-3">Bag is empty</p>';
    } else {
        cart.forEach((item, index) => {
            total += item.price;
            list.innerHTML += `
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-4" style="background: rgba(255,255,255,0.05)">
                    <span class="fw-bold small">${item.name}</span>
                    <i class="bi bi-trash-fill text-danger cyber-sfx-trigger" style="cursor:pointer" onclick="removeItem(${index})"></i>
                </div>`;
        });
        // Re-bind SFX untuk icon tong sampah yang baru dirender
        document.querySelectorAll('.bi-trash-fill').forEach(btn => {
            btn.addEventListener('click', () => playCyberSound('click'));
        });
    }
    document.getElementById('cart-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    localStorage.setItem('sethmacy_cart', JSON.stringify(cart));
}

function addToCart(id, name, price) {
    playCyberSound('success'); // Suara berbeda saat nambah item
    cart.push({id, name, price});
    updateUI();
    Swal.fire({ 
        toast: true, position: 'top-end', icon: 'success', 
        title: 'DATA CAPTURED: ' + name, 
        background: '#0a0a0f', color: '#fff', 
        showConfirmButton: false, timer: 1500 
    });
}

function removeItem(index) {
    cart.splice(index, 1);
    updateUI();
}

function goToCheckout() {
    if(cart.length === 0) return Swal.fire('Error', 'Your bag is empty!', 'error');
    playCyberSound('success');
    document.getElementById('splash-loading').style.display = 'flex';
    setTimeout(() => { window.location.href = 'checkout'; }, 2500);
}

function openHistory() {
    const list = document.getElementById('history-list');
    const myHistory = JSON.parse(localStorage.getItem('sethmacy_history')) || [];
    list.innerHTML = myHistory.length === 0 ? '<p class="text-center opacity-50 py-5">NO DATA FOUND</p>' : '';
    myHistory.reverse().forEach(order => {
        list.innerHTML += `
            <div class="history-card">
                <div class="d-flex justify-content-between mb-2">
                    <b class="text-info">${order.txId}</b>
                    <small class="opacity-50">${order.date}</small>
                </div>
                <div class="small mb-2 text-secondary">${order.items.join(', ')}</div>
                <div class="fw-bold text-white">Total: Rp ${order.total.toLocaleString('id-ID')}</div>
            </div>`;
    });
    new bootstrap.Modal(document.getElementById('historyModal')).show();
}

particlesJS('particles-js', {
    "particles": {
        "number": { "value": 100, "density": { "enable": true, "value_area": 800 } },
        "color": { "value": ["#00d2ff", "#ae00ff", "#ffffff"] },
        "shape": { "type": "circle" },
        "opacity": { "value": 0.4 },
        "size": { "value": 3, "random": true },
        "line_linked": { "enable": true, "distance": 150, "color": "#0d8aff", "opacity": 0.2, "width": 1 },
        "move": { "enable": true, "speed": 3, "direction": "none", "out_mode": "out" }
    },
    "interactivity": { "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } } },
    "retina_detect": true
});

updateUI();
VanillaTilt.init(document.querySelectorAll(".product-card"));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>