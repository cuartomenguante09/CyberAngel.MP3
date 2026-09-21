<?php
$host = "localhost";
$dbname = "cyber_angel_db";
$username = "root";
$password = "";

$chats = [];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt =$pdo->query("SELECT * FROM mensajes ORDER BY id DESC");
    $chats =$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo silencioso de error si la base de datos no responde
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Angel mp3 - Mensajes</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Slackey&display=swap" rel="stylesheet">
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

        .falling-item svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        @keyframes fall {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.95; }
            100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
        }

        @keyframes pulseAndMoveStars {
            0% { transform: translateY(0px) scale(1) rotate(0deg); }
            50% { transform: translateY(-8px) scale(1.18) rotate(6deg); }
            100% { transform: translateY(0px) scale(1) rotate(0deg); }
        }

        .bg-stars {
            position: absolute;
            bottom: 30px;
            left: 20px;
            pointer-events: none;
            z-index: 1;
            display: flex;
            gap: 6px;
            align-items: flex-end;
            animation: pulseAndMoveStars 2.5s ease-in-out infinite;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background-image: url('../img/fondo.jfif');
            background-repeat: repeat;
            background-size: 600px auto;
            font-family: 'Comic Sans MS', 'Courier New', sans-serif;
            color: #000000;
        }

        /* Pantalla completa de mensajes */
        .fullscreen-messages {
            width: 100vw;
            height: calc(100vh - 56px); /* Restamos la barra de navegación inferior */
            background: #000000;
            position: relative;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            padding: 20px;
            overflow: hidden;
            z-index: 2;
        }

        .search-bar-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 3;
            flex-shrink: 0;
        }

        .search-box {
            background: linear-gradient(90deg, #ff66b2, #ff007f, #ffb6c1);
            border-radius: 20px;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 0 12px rgba(255, 0, 127, 0.6);
            border: 1px solid #ffffff;
        }

        .search-box svg {
            width: 16px;
            height: 16px;
            fill: #ffffff;
            flex-shrink: 0;
        }

        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: #ffffff;
            font-family: 'Comic Sans MS', sans-serif;
            font-size: 14px;
            width: 100%;
        }

        .search-box input::placeholder {
            color: #ffffff;
            opacity: 0.9;
        }

        /* Lista de chats con scroll propio */
        .chat-list-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding-right: 5px;
            position: relative;
            z-index: 3;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #111111;
        }
        ::-webkit-scrollbar-thumb {
            background: #ff007f;
            border-radius: 5px;
        }

        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding-bottom: 12px;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.2s;
        }

        .chat-item:hover {
            opacity: 0.85;
        }

        .chat-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #ffb6c1, #ff007f, #ffb6c1, transparent);
        }

        .chat-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar-container {
            position: relative;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 8px rgba(255, 0, 127, 0.7);
            border: 2px solid #ffffff;
            display: block;
        }

        .online-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 11px;
            height: 11px;
            background-color: #39ff14;
            border-radius: 50%;
            border: 2px solid #000000;
            box-shadow: 0 0 5px #39ff14;
        }

        .chat-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .chat-name {
            font-size: 13px;
            font-weight: bold;
            font-style: italic;
        }

        .name-maiti { color: #3b82f6; }
        .name-laura { color: #a855f7; }
        .name-azu { color: #22c55e; }
        .name-sofia { color: #ec4899; }

        .chat-preview {
            background: transparent;
            border: 1px solid #ffb6c1;
            border-radius: 12px;
            padding: 3px 12px;
            font-size: 11px;
            color: #ffffff;
            display: inline-block;
            width: fit-content;
            box-shadow: 0 0 5px rgba(255, 182, 193, 0.3);
        }

        .chat-time {
            font-size: 11px;
            color: #ffffff;
            font-family: 'Courier New', monospace;
        }

        /* Barra de navegación inferior fija */
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

    <div class="fullscreen-messages">
        
        <div class="search-bar-wrapper">
            <div class="search-box">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" id="searchInput" placeholder="Buscar chats o contactos">
            </div>
        </div>

        <div class="chat-list-scroll">
            <div class="chat-list" id="chatList">
                <?php if (!empty($chats)): ?>
                    <?php foreach ($chats as$chat): ?>
                        <a href="chat.php?user=<?php echo htmlspecialchars($chat['usuario_id']); ?>" class="chat-item" data-name="<?php echo strtolower(htmlspecialchars($chat['nombre_contacto'])); ?>">
                            <div class="chat-left">
                                <div class="avatar-container">
                                    <img src="<?php echo htmlspecialchars($chat['avatar']); ?>" alt="<?php echo htmlspecialchars($chat['nombre_contacto']); ?>" class="avatar">
                                    <div class="online-dot"></div>
                                </div>
                                <div class="chat-info">
                                    <span class="chat-name <?php echo htmlspecialchars($chat['clase_color']); ?>"><?php echo htmlspecialchars($chat['nombre_contacto']); ?></span>
                                    <div class="chat-preview"><?php echo htmlspecialchars($chat['preview']); ?></div>
                                </div>
                            </div>
                            <div class="chat-time"><?php echo htmlspecialchars($chat['hora']); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #ff99cc; font-size: 13px;">No hay chats disponibles.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-stars">
            <svg width="45" height="45" viewBox="0 0 100 100" fill="#ff007f">
                <polygon points="50,0 61,35 98,35 68,57 79,91 50,70 21,91 32,57 2,35 39,35" opacity="0.9"/>
            </svg>
            <svg width="32" height="32" viewBox="0 0 100 100" fill="#ff007f">
                <polygon points="50,0 61,35 98,35 68,57 79,91 50,70 21,91 32,57 2,35 39,35" opacity="0.9"/>
            </svg>
        </div>

    </div>

    <!-- Barra de navegación inferior conectada a perfil.php -->
    <div class="bottom-bar">
        <button class="nav-item" onclick="window.location.href='perfil.php'" title="Perfil">
            <img src="../img/perfil.jfif" alt="Perfil">
        </button>
        <button class="nav-item" title="Match" onclick="window.location.href='match.php'">
            <img src="../img/match.jfif" alt="Match">
        </button>
        <button class="nav-item" title="Agregar" onclick="window.location.href='agregar.php'">
            <img src="../img/simbolomas.jfif" alt="Agregar">
        </button>
        <button class="nav-item active" title="Mensajes" onclick="window.location.href='mensajes.php'">
            <img src="../img/mensajes.jfif" alt="Mensajes">
        </button>
        <button class="nav-item" title="Notificaciones" onclick="window.location.href='notificaciones.php'">
            <img src="../img/notificaciones.jfif" alt="Notificaciones">
        </button>
    </div>

    <script>
        // Buscador de chats en tiempo real
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const chatItems = document.querySelectorAll('.chat-item');
            
            chatItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(term)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Efectos de estrellas y brillos Y2K
        const Y2K_ITEMS = [
            `<svg viewBox="0 0 24 24" fill="#ff007f"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ffb6c1"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ff007f"><path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5Z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ffffff" stroke="#ff007f" stroke-width="1.5"><path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5Z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ff66b2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`
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

            setTimeout(() => {
                item.remove();
            }, duration * 1000);
        }

        setInterval(createFallingItem, 300);

        const colors = ['#ff007f', '#ff66b2', '#ffffff', '#ffb6c1'];
        let lastSparkleTime = 0;

        document.addEventListener('mousemove', function(e) {
            const now = Date.now();
            if (now - lastSparkleTime > 40) {
                createSparkle(e.clientX, e.clientY);
                lastSparkleTime = now;
            }
        });

        function createSparkle(x, y) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            
            const offsetX = (Math.random() - 0.5) * 15;
            const offsetY = (Math.random() - 0.5) * 15;
            sparkle.style.left = (x + offsetX) + 'px';
            sparkle.style.top = (y + offsetY) + 'px';
            
            sparkle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            
            const size = Math.random() * 8 + 8;
            sparkle.style.width = size + 'px';
            sparkle.style.height = size + 'px';

            document.body.appendChild(sparkle);

            setTimeout(() => {
                sparkle.remove();
            }, 800);
        }
    </script>
</body>
</html>