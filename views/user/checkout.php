<?php if (!isset($_SESSION['role'])) { header("Location: login"); exit(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECURE CHECKOUT | sethmacy.hub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            position: relative;
        }

        /* Particles.js Background */
        #particles-js {
            position: fixed; width: 100%; height: 100%; z-index: -2; top: 0; left: 0;
        }

        /* Dynamic Glow Background */
        body::before {
            content: ''; position: fixed; width: 500px; height: 500px;
            background: radial-gradient(circle, var(--neon-blue) 0%, var(--neon-purple) 40%, transparent 70%);
            filter: blur(180px); border-radius: 50%; z-index: -1;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            opacity: 0.3; animation: moveGlow 15s infinite alternate ease-in-out;
        }

        @keyframes moveGlow {
            0% { transform: translate(-60%, -60%) scale(1); }
            100% { transform: translate(40%, 40%) scale(1.2); }
        }

        /* Intro Splash */
        #intro-splash {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: var(--cyber-dark); z-index: 10000;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            transition: opacity 0.8s ease, visibility 0.8s;
        }

        .scan-line {
            width: 300px; height: 3px; background: var(--neon-blue);
            box-shadow: 0 0 20px var(--neon-blue); position: relative;
            animation: scan 2s linear infinite;
        }

        @keyframes scan { 
            0%, 100% { transform: translateY(-50px); opacity: 0; } 
            50% { transform: translateY(50px); opacity: 1; } 
        }

        /* Cyber Glass Card */
        .checkout-glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 210, 255, 0.2);
            border-radius: 40px;
            padding: 50px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            transition: 0.4s;
        }

        .holographic-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            text-shadow: 0 0 15px var(--neon-blue);
        }

        .order-item-row {
            background: rgba(255,255,255,0.03);
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 12px;
            border-left: 4px solid var(--neon-blue);
            font-family: 'Share Tech Mono', monospace;
        }

        /* Stepper Neon */
        .track-node-container { display: flex; justify-content: space-between; position: relative; margin-top: 40px; }
        .track-main-line { position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: rgba(255,255,255,0.1); z-index: 1; transform: translateY(-50%); }
        .track-progress-fill { position: absolute; top: 50%; left: 0; width: 0%; height: 4px; background: var(--neon-blue); box-shadow: 0 0 15px var(--neon-blue); z-index: 2; transform: translateY(-50%); transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .track-node { width: 50px; height: 50px; border-radius: 50%; background: #111; border: 2px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; z-index: 3; transition: 0.5s; }
        .track-node.completed { background: var(--neon-blue); border-color: var(--neon-blue); color: #000; box-shadow: 0 0 20px var(--neon-blue); }

        /* Neon Button */
        .btn-pay-neon {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
            color: #fff; font-family: 'Orbitron', sans-serif; font-weight: 800;
            border-radius: 50px; padding: 18px; border: none;
            text-transform: uppercase; letter-spacing: 2px;
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.4); transition: 0.4s;
        }
        .btn-pay-neon:hover { background: #fff; color: #000; box-shadow: 0 0 30px #fff; transform: scale(1.02); }

        /* Digital Receipt */
        .receipt-container {
            background: #fff; color: #000; padding: 30px; border-radius: 2px;
            font-family: 'Courier New', monospace;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            clip-path: polygon(0 0, 100% 0, 100% 95%, 95% 100%, 85% 95%, 75% 100%, 65% 95%, 55% 100%, 45% 95%, 35% 100%, 25% 95%, 15% 100%, 5% 95%, 0 100%);
            animation: slideUpReceipt 1s ease-out forwards;
        }

        @keyframes slideUpReceipt { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .receipt-line { border-top: 1px dashed #000; margin: 10px 0; }
        
        .cyber-sfx-trigger { cursor: pointer; }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div id="intro-splash">
    <div class="scan-line"></div>
    <div class="h2 text-info mt-4 holographic-title" style="letter-spacing: 5px;">DECRYPTING...</div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div id="payment-stage">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h4 class="text-info mb-0 fw-bold small" style="font-family: 'Share Tech Mono';">SECURE GATEWAY v4.0</h4>
                        <h1 class="holographic-title display-5">CHECKOUT <span class="text-info">SUMMARY.</span></h1>
                    </div>
                    <a href="catalog" class="btn btn-outline-info btn-sm rounded-pill px-4 mb-2 cyber-sfx-trigger" style="font-family: 'Orbitron';">RETURN TO HUB</a>
                </div>

                <div class="checkout-glass-card shadow-lg">
                    <div id="items-container" class="mb-4"></div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-5 p-4 rounded-4" style="background: rgba(0, 210, 255, 0.05); border: 1px dashed rgba(0, 210, 255, 0.3);">
                        <span class="fs-5 opacity-75" style="font-family: 'Share Tech Mono';">TOTAL PAYABLE:</span>
                        <h2 class="fw-bold text-info mb-0" id="total-val" style="font-family: 'Orbitron';">Rp 0</h2>
                    </div>

                    <button class="btn btn-pay-neon w-100 mt-5 cyber-sfx-trigger" onclick="executePayment()">Initiate Authorization</button>
                </div>
            </div>

            <div id="tracking-stage" class="d-none text-center">
                <div class="mb-5"><i id="live-icon" class="bi bi-cpu-fill display-1 text-info animate-pulse"></i></div>
                <h1 id="track-title" class="holographic-title display-4 mb-2">VERIFYING LEDGER...</h1>
                <p id="track-sub" class="text-secondary fs-5 mb-5" style="font-family: 'Share Tech Mono';">Connecting to decentralized server...</p>

                <div class="checkout-glass-card">
                    <div class="track-node-container mb-5">
                        <div class="track-main-line"></div>
                        <div id="progress-bar" class="track-progress-fill"></div>
                        <div class="track-node completed" id="step-1"><i class="bi bi-wallet2"></i></div>
                        <div class="track-node" id="step-2"><i class="bi bi-box-seam-fill"></i></div>
                        <div class="track-node" id="step-3"><i class="bi bi-truck"></i></div>
                        <div class="track-node" id="step-4"><i class="bi bi-house-check-fill"></i></div>
                    </div>
                    <div class="bg-black bg-opacity-50 py-3 px-5 rounded-pill d-inline-block border border-info border-opacity-25 mt-4">
                        <small class="text-secondary small me-2">TXID:</small>
                        <b class="text-info" id="tracking-number" style="font-family: 'Share Tech Mono';">TX-REF-000000</b>
                    </div>
                </div>
            </div>

            <div id="receipt-stage" class="d-none text-center mt-4">
                <div class="receipt-container mx-auto text-start mb-5" style="max-width: 450px;">
                    <div class="text-center">
                        <h4 class="fw-bold mb-0">sethmacy.hub</h4>
                        <small>NEO-MARKETPLACE V.2026</small>
                    </div>
                    <div class="receipt-line"></div>
                    <p class="small mb-1">TX ID: <span id="r-txid" class="fw-bold"></span></p>
                    <p class="small mb-0">DATE: <span id="r-date"></span></p>
                    <div class="receipt-line"></div>
                    <div id="receipt-items-list" class="small"></div>
                    <div class="receipt-line"></div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>NET TOTAL</span>
                        <span id="r-total"></span>
                    </div>
                    <div class="receipt-line"></div>
                    <div class="text-center mt-4">
                        <p class="small fw-bold mb-1">PAYMENT SUCCESSFUL</p>
                        <i class="bi bi-qr-code fs-1"></i>
                        <p class="small mt-2" style="font-size: 8px;">VERIFIED BY SETHMACY BLOCKCHAIN SYSTEM</p>
                    </div>
                </div>
                <button class="btn btn-outline-info rounded-pill px-5 py-3 fw-bold cyber-sfx-trigger" style="font-family: 'Orbitron';" onclick="window.location.href='catalog'">BACK TO TERMINAL</button>
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
// Event Listener untuk tombol
document.querySelectorAll('.cyber-sfx-trigger').forEach(btn => {
    btn.addEventListener('click', () => playCyberSound('click'));
});
// --- AUDIO ENGINE END ---

let cart = JSON.parse(localStorage.getItem('sethmacy_cart')) || [];
const transactionID = 'TX-' + Math.random().toString(36).substr(2, 9).toUpperCase();

function setupPage() {
    if(cart.length === 0 && !document.getElementById('receipt-stage').classList.contains('d-none')) return;
    let sum = 0;
    const box = document.getElementById('items-container');
    const rList = document.getElementById('receipt-items-list');

    cart.forEach(item => {
        sum += item.price;
        box.innerHTML += `
            <div class="order-item-row d-flex justify-content-between align-items-center">
                <span class="fw-bold">${item.name}</span>
                <span class="text-info">Rp ${item.price.toLocaleString('id-ID')}</span>
            </div>`;
        rList.innerHTML += `
            <div class="d-flex justify-content-between mb-1">
                <span>${item.name}</span>
                <span>${item.price.toLocaleString('id-ID')}</span>
            </div>`;
    });
    
    document.getElementById('total-val').innerText = 'Rp ' + sum.toLocaleString('id-ID');
    document.getElementById('r-total').innerText = 'Rp ' + sum.toLocaleString('id-ID');
    document.getElementById('r-txid').innerText = transactionID;
    document.getElementById('r-date').innerText = new Date().toLocaleString();

    setTimeout(() => {
        const splash = document.getElementById('intro-splash');
        if(splash) {
            splash.style.opacity = '0';
            setTimeout(() => { splash.style.visibility = 'hidden'; }, 800);
        }
    }, 2000);
}

function executePayment() {
    playCyberSound('click');
    Swal.fire({
        title: 'Authorize Payment?',
        text: 'Accessing sethmacy-Pay blockchain network...',
        icon: 'info',
        background: '#0a0a0f', color: '#fff',
        confirmButtonColor: '#00d2ff',
        showCancelButton: true
    }).then(res => {
        if(res.isConfirmed) {
            playCyberSound('success');
            document.getElementById('payment-stage').classList.add('d-none');
            document.getElementById('tracking-stage').classList.remove('d-none');
            runLiveTracking();
        }
    });
}

function runLiveTracking() {
    document.getElementById('tracking-number').innerText = transactionID;
    const timeline = [
        { id: 'step-2', icon: 'bi-box-seam-fill', title: 'HARDWARE PACKED', sub: 'Secure holographic packaging complete.', bar: '33.3%' },
        { id: 'step-3', icon: 'bi-truck', title: 'DISPATCHING', sub: 'Drone delivery en route to your node.', bar: '66.6%' },
        { id: 'step-4', icon: 'bi-house-check-fill', title: 'SUCCESSFULLY ARRIVED', sub: 'Transaction verified and completed.', bar: '100%' }
    ];

    let currentIdx = 0;
    const sequence = setInterval(() => {
        if(currentIdx < timeline.length) {
            playCyberSound('click'); // Suara setiap langkah tracking
            document.getElementById(timeline[currentIdx].id).classList.add('completed');
            document.getElementById('progress-bar').style.width = timeline[currentIdx].bar;
            document.getElementById('track-title').innerText = timeline[currentIdx].title;
            document.getElementById('track-sub').innerText = timeline[currentIdx].sub;
            document.getElementById('live-icon').className = 'bi ' + timeline[currentIdx].icon + ' display-1 text-info';
            currentIdx++;
        } else {
            clearInterval(sequence);
            showFinalReceipt();
        }
    }, 3000); 
}

function showFinalReceipt() {
    playCyberSound('success');
    let history = JSON.parse(localStorage.getItem('sethmacy_history')) || [];
    history.push({
        txId: transactionID,
        date: new Date().toLocaleString('id-ID'),
        total: cart.reduce((sum, item) => sum + item.price, 0),
        items: cart.map(item => item.name)
    });
    localStorage.setItem('sethmacy_history', JSON.stringify(history));

    confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#00d2ff', '#ffffff', '#ae00ff'] });
    setTimeout(() => {
        document.getElementById('tracking-stage').classList.add('d-none');
        document.getElementById('receipt-stage').classList.remove('d-none');
        localStorage.removeItem('sethmacy_cart');
    }, 1500);
}

// Particles Config (Matching Catalog/Login)
particlesJS('particles-js', {
    "particles": {
        "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
        "color": { "value": ["#00d2ff", "#ae00ff", "#ffffff"] },
        "shape": { "type": "circle" },
        "opacity": { "value": 0.4, "random": true },
        "size": { "value": 3, "random": true },
        "line_linked": { "enable": true, "distance": 150, "color": "#0d8aff", "opacity": 0.2, "width": 1 },
        "move": { "enable": true, "speed": 2, "direction": "none", "out_mode": "out" }
    },
    "interactivity": {
        "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } }
    },
    "retina_detect": true
});

setupPage();
</script>
</body>
</html>