<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Angel - Intro</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Slackey&display=swap" rel="stylesheet">
    <style>
        *, body, a, button, select, input {
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="%23ffb6c1" stroke="%23ff007f" stroke-width="1.5"><path d="M4.5 3.5L11.5 20.5L14.5 13.5L21.5 10.5L4.5 3.5Z"/></svg>'), auto !important;
        }

        .sparkle {
            position: fixed;
            pointer-events: none;
            width: 12px;
            height: 12px;
            background-color: #ff007f;
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            z-index: 9999;
            transform: translate(-50%, -50%) scale(1);
            animation: sparkle-anim 0.8s forwards linear;
        }

        @keyframes sparkle-anim {
            0% { opacity: 1; transform: translate(-50%, -50%) scale(1.2) rotate(0deg); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(0.2) translateY(20px) rotate(180deg); }
        }

        .falling-item {
            position: fixed;
            top: -50px;
            pointer-events: none;
            z-index: 1;
            user-select: none;
            animation: fall linear forwards;
        }
        .falling-item svg { width: 100%; height: 100%; display: block; }
        @keyframes fall {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.95; }
            100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
        }

        html { overflow-y: scroll; }

        body {
            margin: 0;
            padding: 0;
            background-image: url('img/animal.jfif');
            background-repeat: repeat;
            background-size: 500px auto;
            font-family: 'Comic Sans MS', 'Courier New', sans-serif;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
            background-color: #000000;
        }

        #loader-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #000000;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 1s ease;
        }

        .loader-top-left {
            position: absolute;
            top: 20px;
            left: 25px;
            font-family: 'Great Vibes', cursive;
            font-size: 20px;
            color: #ff99cc;
            line-height: 1.1;
            text-shadow: 1px 1px #ff007f;
            pointer-events: none;
        }

        .loader-logo {
            width: 320px;
            max-width: 80vw;
            height: auto;
            object-fit: contain;
            margin-bottom: 25px;
        }

        .loader-bar-container {
            width: 240px;
            height: 20px;
            background: #220011;
            border: 2px solid #ff66b2;
            border-radius: 4px;
            display: flex;
            padding: 2px;
            gap: 2px;
            box-shadow: 0 0 10px rgba(255, 0, 127, 0.4);
        }

        .loader-block {
            flex: 1;
            height: 100%;
            background: #440022;
            border-radius: 1px;
            transition: background 0.1s;
        }

        .loader-block.active {
            background: linear-gradient(180deg, #ff99cc, #ff3399);
            box-shadow: 0 0 6px #ff007f;
        }

        .loader-percentage {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #ff66b2;
            margin-top: 10px;
            text-shadow: 1px 1px #000;
            letter-spacing: 0.5px;
        }

        #load-btn {
            margin-top: 18px;
            background: linear-gradient(180deg, #ff99cc, #ff007f);
            color: #ffffff;
            border: 2px solid #ffffff;
            padding: 8px 24px;
            font-family: 'Slackey', cursive;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: 0 0 15px #ff007f;
            cursor: pointer;
            letter-spacing: 1px;
            transition: transform 0.2s, background 0.2s;
        }

        #load-btn:hover {
            transform: scale(1.08);
            background: linear-gradient(180deg, #ffb6c1, #ff3399);
        }
    </style>
</head>
<body>

    <audio id="intro-audio" src="img/introcancion.mp3" preload="auto"></audio>

    <div id="loader-screen">
        <div class="loader-top-left">Cyber<br>Angel<br>.mp3</div>
        <img src="img/logo.png" alt="Logo Cyber Angel" class="loader-logo">
        <div class="loader-bar-container" id="block-container">
            <?php for ($i = 0; $i < 10; $i++): ?>
                <div class="loader-block"></div>
            <?php endfor; ?>
        </div>
        <div class="loader-percentage" id="loader-perc">0% complete</div>
        <button id="load-btn" onclick="startLoadingProcess()">Cargar</button>
    </div>

    <script>
        const introAudio = document.getElementById('intro-audio');
        const loaderScreen = document.getElementById('loader-screen');
        const loaderPerc = document.getElementById('loader-perc');
        const blocks = document.querySelectorAll('.loader-block');
        const loadBtn = document.getElementById('load-btn');

        let loadProgress = 0;

        function startLoadingProcess() {
            loadBtn.style.display = 'none';
            introAudio.play().catch(() => {});

            const loadInterval = setInterval(() => {
                loadProgress += 1;
                if (loadProgress > 100) loadProgress = 100;

                loaderPerc.textContent = loadProgress + '% complete';

                const activeBlocksCount = Math.floor((loadProgress / 100) * blocks.length);
                blocks.forEach((block, index) => {
                    if (index < activeBlocksCount) {
                        block.classList.add('active');
                    }
                });

                if (loadProgress >= 100) {
                    clearInterval(loadInterval);
                    
                    setTimeout(() => {
                        loaderScreen.style.opacity = '0';
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 1000);
                    }, 400);
                }
            }, 150);
        }

        const Y2K_ITEMS = [
            `<svg viewBox="0 0 24 24" fill="#ff007f"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ffb6c1"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ff007f"><path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5Z"/></svg>`
        ];

        function createFallingItem() {
            const item = document.createElement('div');
            item.className = 'falling-item';
            item.innerHTML = Y2K_ITEMS[Math.floor(Math.random() * Y2K_ITEMS.length)];
            item.style.left = Math.random() * 100 + 'vw';
            const size = Math.random() * 10 + 16;
            item.style.width = size + 'px';
            item.style.height = size + 'px';
            const duration = Math.random() * 3 + 4;
            item.style.animationDuration = duration + 's';
            document.body.appendChild(item);
            setTimeout(() => item.remove(), duration * 1000);
        }
        setInterval(createFallingItem, 400);

        const colors = ['#ff007f', '#ff66b2', '#ffffff', '#ffb6c1'];
        let lastSparkleTime = 0;
        document.exitPointerLock = function(){};
        document.addEventListener('mousemove', function(e) {
            const now = Date.now();
            if (now - lastSparkleTime > 50) {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.style.left = e.clientX + 'px';
                sparkle.style.top = e.clientY + 'px';
                sparkle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                document.body.appendChild(sparkle);
                setTimeout(() => sparkle.remove(), 800);
                lastSparkleTime = now;
            }
        });
    </script>
</body>
</html>