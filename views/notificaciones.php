<?php

$notificaciones = [
    "Maliiittteee comenzó a seguirte",
    "Lauuuraaa te envió un post",
    "Azzzuuuuumyyy envió un mensaje",
    "Azzzuuuuumyyy dio like a tu último post",
    "Sooofiiiaaaaaa envió solicitud de MATCH!"
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Angel - Notificaciones</title>
    
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

        html { overflow-y: scroll; }

        ::-webkit-scrollbar { width: 16px; background-color: #ffe4e1; }
        ::-webkit-scrollbar-track { background: #ffd0e3; border-left: 2px solid #ff007f; }
        ::-webkit-scrollbar-thumb { background: #ff66b2; border: 2px solid #ffffff; outline: 1px solid #ff007f; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #ff007f; }

        body {
            margin: 0;
            padding: 0;
            background-image: url('../img/animal.jfif');
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

        .window-container {
            width: 100%;
            max-width: 800px;
            background-color: #000000;
            min-height: calc(100vh - 56px);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px 100px 20px;
            box-sizing: border-box;
            border-left: 12px solid #ff007f;
            border-right: 12px solid #ff007f;
            box-shadow: inset 0 0 30px rgba(255, 0, 127, 0.3);
        }

        .bg-decor-text {
            position: absolute;
            font-family: 'Great Vibes', cursive;
            color: rgba(255, 102, 178, 0.25);
            font-size: 28px;
            pointer-events: none;
            text-shadow: 1px 1px #ff007f;
            z-index: 1;
        }

        .t1 { top: 40px; left: 40px; transform: rotate(-10deg); }
        .t2 { top: 80px; right: 50px; transform: rotate(15deg); font-size: 34px; }
        .t3 { bottom: 120px; left: 60px; transform: rotate(12deg); }
        .t4 { bottom: 100px; right: 60px; transform: rotate(-8deg); font-size: 32px; }

        .notifications-list {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 22px;
            width: 100%;
            max-width: 550px;
            align-items: center;
        }

        .notification-card {
            width: 100%;
            background: linear-gradient(180deg, #ff99cc, #ff007f);
            border: 2px solid #ffffff;
            border-radius: 40px;
            padding: 16px 25px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 13.5px;
            font-weight: bold;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(255, 0, 127, 0.7), inset 0 2px 6px rgba(255, 255, 255, 0.6);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
            cursor: pointer;
        }

        .notification-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 22px #ff007f, inset 0 2px 8px #ffffff;
            background: linear-gradient(180deg, #ffb6c1, #ff1493);
        }

        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 56px;
            background: rgba(255, 182, 193, 0.95);
            backdrop-filter: blur(6px);
            border-top: 3px solid #ff007f;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 99;
            box-sizing: border-box;
            padding: 0 10px;
        }

        .nav-item {
            background: transparent;
            border: none;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.15s ease-in-out;
            border-radius: 50%;
            cursor: pointer;
        }

        .nav-item img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ff007f;
            box-shadow: 0 0 6px rgba(255, 0, 127, 0.4);
            pointer-events: none;
        }

        .nav-item:hover {
            transform: scale(1.15) translateY(-2px);
            filter: drop-shadow(0 0 8px #ff007f);
        }

        .nav-item:active {
            transform: scale(0.95);
        }

        .nav-item.active img {
            border: 3px solid #ffffff;
            box-shadow: 0 0 12px #ff007f;
            transform: scale(1.1);
        }
    </style>
</head>
<body>

    <div class="window-container">
        <div class="bg-decor-text t1">NEW NOTIFICATIONS !!!<br>\(>v<)/</div>
        <div class="bg-decor-text t2">Diva 💋</div>
        <div class="bg-decor-text t3">\(>v<)/</div>
        <div class="bg-decor-text t4">NEW NOTIFICATIONS !!!<br>Diva 💋</div>

        <div class="notifications-list">
            <?php foreach ($notificaciones as $notif): ?>
                <div class="notification-card"><?php echo htmlspecialchars($notif); ?></div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bottom-bar">
        <button class="nav-item" id="profile-btn" title="Clic izq: Volver al Home | Clic der: Perfil">
            <img src="../img/perfil.jfif" alt="Perfil">
        </button>
        <button class="nav-item" id="match-btn" title="Match">
            <img src="../img/match.jfif" alt="Match">
        </button>
        <button class="nav-item" id="add-btn" title="Agregar">
            <img src="../img/simbolomas.jfif" alt="Agregar">
        </button>
        <button class="nav-item" id="messages-btn" title="Mensajes">
            <img src="../img/mensajes.jfif" alt="Mensajes">
        </button>
        <button class="nav-item active" id="notifications-btn" title="Notificaciones">
            <img src="../img/notificaciones.jfif" alt="Notificaciones">
        </button>
    </div>

    <script>
        const profileBtn = document.getElementById('profile-btn');
        if (profileBtn) {
            profileBtn.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });

            profileBtn.addEventListener('mousedown', function(e) {
                if (e.button === 0) {
                    window.location.href = 'home.php'; 
                } else if (e.button === 2) {
                    window.location.href = 'perfil.php';
                }
            });
        }

        const matchBtn = document.getElementById('match-btn');
        if (matchBtn) {
            matchBtn.addEventListener('click', function() {
                window.location.href = 'match.php';
            });
        }

        const addBtn = document.getElementById('add-btn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                window.location.href = 'agregar.php';
            });
        }

        const messagesBtn = document.getElementById('messages-btn');
        if (messagesBtn) {
            messagesBtn.addEventListener('click', function() {
                window.location.href = 'mensajes.php';
            });
        }

        const notificationsBtn = document.getElementById('notifications-btn');
        if (notificationsBtn) {
            notificationsBtn.addEventListener('click', function() {
                window.location.href = 'notificaciones.php';
            });
        }

        const colors = ['#ff007f', '#ff66b2', '#ffffff', '#ffb6c1'];
        let lastSparkleTime = 0;
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