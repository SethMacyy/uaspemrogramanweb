<?php
/**
 * File: views/login.php
 * Deskripsi: Halaman login Cyberpunk Ultra-Futuristic sethmacy.hub
 */

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_btn'])) {
    $auth = new AuthController($db);
    $username = isset($_POST['user_input']) ? $_POST['user_input'] : '';
    $password = isset($_POST['pass_input']) ? $_POST['pass_input'] : '';

    $user = $auth->login($username, $password);

    if ($user) {
        if ($_SESSION['role'] == 'admin') {
            header("Location: index.php?url=admin/dashboard");
        } else {
            header("Location: index.php?url=catalog");
        }
        exit();
    } else {
        $error = "ACCESS DENIED: Identity Mismatch.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TERMINAL ACCESS | sethmacy.hub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Particles Background */
        #particles-js { position: absolute; width: 100%; height: 100%; z-index: -2; top: 0; left: 0; }

        /* Animated Background Glow (Sama dengan Catalog) */
        body::before {
            content: ''; position: absolute; width: 500px; height: 500px;
            background: radial-gradient(circle, var(--neon-blue) 0%, var(--neon-purple) 40%, transparent 70%);
            filter: blur(150px); border-radius: 50%; z-index: -1;
            opacity: 0.3; animation: moveGlow 15s infinite alternate ease-in-out;
        }

        @keyframes moveGlow {
            from { transform: translate(-30%, -30%) scale(1); }
            to { transform: translate(30%, 30%) scale(1.2); }
        }

        /* Splash Screen */
        #login-splash {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(10, 10, 15, 0.95); z-index: 9999;
            display: none; flex-direction: column; align-items: center; justify-content: center;
            backdrop-filter: blur(25px); animation: fadeIn 0.4s ease;
        }

        .loader-orbit {
            width: 120px; height: 120px; border: 3px solid transparent;
            border-top: 3px solid var(--neon-blue); border-radius: 50%;
            animation: spin 1s linear infinite; position: relative;
        }
        .loader-orbit::before {
            content: ''; position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 2px solid transparent; border-top: 2px solid var(--neon-purple);
            border-radius: 50%; animation: spin 2s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Login Card - Modern Glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 210, 255, 0.2);
            border-radius: 40px;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);
            transform-style: preserve-3d;
            transition: 0.5s ease;
        }

        .holographic-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900; font-size: 2.5rem;
            text-shadow: 0 0 15px var(--neon-blue), 0 0 30px var(--neon-purple);
            letter-spacing: 2px;
            transform: translateZ(50px);
        }

        .subtitle-glitch {
            font-family: 'Share Tech Mono', monospace;
            color: var(--neon-blue);
            letter-spacing: 3px; font-size: 0.8rem;
            margin-bottom: 40px;
            transform: translateZ(30px);
        }

        /* Form Styling */
        .input-group {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
            transform: translateZ(20px);
        }
        .input-group:focus-within {
            border-color: var(--neon-blue);
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
        }

        .form-control {
            background: transparent !important;
            border: none !important;
            color: #fff !important;
            padding: 12px 15px;
            font-family: 'Share Tech Mono', monospace;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.2); }

        /* Neon Button */
        .btn-login {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
            color: #fff; font-family: 'Orbitron', sans-serif;
            font-weight: 800; border: none; border-radius: 50px;
            padding: 15px; margin-top: 20px;
            text-transform: uppercase; letter-spacing: 2px;
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.4);
            transition: 0.4s;
            transform: translateZ(40px);
        }
        .btn-login:hover {
            background: #fff; color: #000;
            box-shadow: 0 0 30px #fff;
            transform: translateZ(45px) scale(1.05);
        }

        .alert-cyber {
            background: rgba(255, 71, 87, 0.1);
            border: 1px solid #ff4757;
            color: #ff4757;
            font-family: 'Share Tech Mono';
            font-size: 0.8rem;
            border-radius: 15px;
        }
    </style>
</head>
<body>

    <div id="login-splash">
        <div class="loader-orbit"></div>
        <div class="mt-4 text-center">
            <span id="status-text" class="text-info fw-bold" style="font-family: 'Orbitron'; letter-spacing: 3px;">INITIALIZING...</span>
        </div>
    </div>

    <div id="particles-js"></div>

    <div class="login-card text-center" data-tilt data-tilt-max="10" data-tilt-glare="true" data-tilt-max-glare="0.2">
        <div class="holographic-title">sethmacy<span class="text-info">.hub</span></div>
        <p class="subtitle-glitch">/// SYSTEM AUTHENTICATION ///</p>

        <?php if($error): ?>
            <div class="alert alert-cyber py-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="login-form">
            <div class="text-start mb-3">
                <label class="small ms-2 text-info" style="font-family: 'Share Tech Mono';">USER_ID</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-transparent text-info"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="user_input" class="form-control sfx-input" placeholder="Enter username" required autocomplete="off">
                </div>
            </div>

            <div class="text-start mb-4">
                <label class="small ms-2 text-info" style="font-family: 'Share Tech Mono';">ACCESS_KEY</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-transparent text-info"><i class="bi bi-shield-lock-fill"></i></span>
                    <input type="password" name="pass_input" id="password_field" class="form-control sfx-input" placeholder="••••••••" required>
                    <span class="input-group-text border-0 bg-transparent text-info" id="togglePassword" style="cursor:pointer;">
                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" name="login_btn" class="btn btn-login w-100" id="submit-btn">
                LOGIN <i class="bi bi-chevron-right ms-2"></i>
            </button>
        </form>

        <div class="mt-5 d-flex justify-content-between opacity-50">
            <a href="#" class="small text-white text-decoration-none" style="font-family: 'Share Tech Mono';">LOST_ACCESS?</a>
            <span class="small text-white" style="font-family: 'Share Tech Mono';">V.4.5.0</span>
        </div>
    </div>

    <script>
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
                osc.start(); osc.stop(audioCtx.currentTime + 0.1);
            } else if (type === 'confirm') {
                osc.type = 'square'; osc.frequency.setValueAtTime(300, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(800, audioCtx.currentTime + 0.3);
                gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
                osc.start(); osc.stop(audioCtx.currentTime + 0.3);
            }
        }

        // --- INTERACTION ---
        document.querySelectorAll('.sfx-input').forEach(el => el.addEventListener('focus', () => playCyberSound('click')));
        document.getElementById('togglePassword').addEventListener('click', () => playCyberSound('click'));

        const loginForm = document.getElementById('login-form');
        const splash = document.getElementById('login-splash');
        const statusText = document.getElementById('status-text');
        const messages = ["AUTHORIZING...", "DECRYPTING KEY...", "SYNCING NODES...", "ACCESS GRANTED"];

        loginForm.addEventListener('submit', function() {
            splash.style.display = 'flex';
            playCyberSound('confirm');
            let i = 0;
            const interval = setInterval(() => {
                statusText.innerText = messages[i];
                playCyberSound('click');
                i++;
                if (i >= messages.length) clearInterval(interval);
            }, 600);
        });

        // --- PARTICLES ---
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 85 },
                "color": { "value": ["#00d2ff", "#ae00ff"] },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5 },
                "size": { "value": 3 },
                "line_linked": { "enable": true, "distance": 150, "color": "#00d2ff", "opacity": 0.2 },
                "move": { "enable": true, "speed": 2 }
            }
        });

        // --- SHOW/HIDE PASSWORD ---
        const toggleBtn = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password_field');
        const iconMata = document.querySelector('#toggleIcon');
        toggleBtn.addEventListener('click', function () {
            const typeInput = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', typeInput);
            iconMata.classList.toggle('bi-eye');
            iconMata.classList.toggle('bi-eye-slash');
        });

        // --- INIT 3D TILT ---
        VanillaTilt.init(document.querySelector(".login-card"));
    </script>
</body>
</html>