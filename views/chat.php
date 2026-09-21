<?php
$host = "localhost";
$dbname = "cyber_angel_db";
$username = "root";
$password = "";


$usuario_id = isset($_GET['user']) ? strtolower(trim($_GET['user'])) : 'maite';


$contacto = [
    'nombre_contacto' => 'Sofi',
    'avatar' => '../img/perfil.jfif',
    'mensajes' => [
        ['tipo' => 'incoming', 'texto' => 'like si te gustan los alfajores'],
        ['tipo' => 'outgoing', 'texto' => 'like'],
        ['tipo' => 'incoming', 'texto' => 'AMO']
    ]
];

if ($usuario_id === 'maite') {$contacto = [
        'nombre_contacto' => 'Maite',
        'avatar' => '../img/maite.jfif',
        'mensajes' => [
            ['tipo' => 'incoming', 'texto' => 'holiii amiga'],
            ['tipo' => 'outgoing', 'texto' => 'HOLA AMIGAAA'],
            ['tipo' => 'incoming', 'tipo_contenido' => 'sticker', 'texto' => '⭐ [Sticker enviado] 💖']
        ]
    ];
} elseif ($usuario_id === 'lau') {$contacto = [
        'nombre_contacto' => 'Lau',
        'avatar' => '../img/lau.jfif',
        'mensajes' => [
            ['tipo' => 'incoming', 'texto' => 'hola me gustan gordos'],
            ['tipo' => 'outgoing', 'texto' => 'a mi flacos'],
            ['tipo' => 'incoming', 'texto' => 'AMO']
        ]
    ];
} elseif ($usuario_id === 'azu') {$contacto = [
        'nombre_contacto' => 'Azu',
        'avatar' => '../img/azumy.jpg',
        'mensajes' => [
            ['tipo' => 'incoming', 'texto' => 'TENGO HAMBRE'],
            ['tipo' => 'outgoing', 'texto' => 'YO TAMBIEN'],
            ['tipo' => 'incoming', 'texto' => 'quieroqueque']
        ]
    ];
} else {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt =$pdo->prepare("SELECT * FROM mensajes WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $res =$stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {$contacto['nombre_contacto'] = $res['nombre_contacto'] ?? $usuario_id;
            if (!empty($res['avatar'])) {
                $contacto['avatar'] =$res['avatar'];
            }
        }
    } catch (PDOException $e) {
        
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Angel mp3 - Chat con <?php echo htmlspecialchars($contacto['nombre_contacto']); ?></title>
    
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

        .fullscreen-chat {
            width: 100vw;
            height: calc(100vh - 56px);
            background: #000000;
            position: relative;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            z-index: 2;
        }

        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            background: linear-gradient(90deg, #1a001a, #330033);
            border-bottom: 2px solid #ff007f;
            box-shadow: 0 2px 10px rgba(255, 0, 127, 0.4);
            z-index: 3;
            flex-shrink: 0;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .back-btn {
            background: transparent;
            border: none;
            color: #ffb6c1;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .back-btn:hover {
            transform: scale(1.1);
            color: #ff007f;
        }

        .chat-header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff007f;
            box-shadow: 0 0 6px rgba(255, 0, 127, 0.6);
        }

        .chat-header-name {
            font-size: 14px;
            font-weight: bold;
            font-style: italic;
            color: #ffffff;
        }

        .chat-body {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
            z-index: 3;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #111111;
        }
        ::-webkit-scrollbar-thumb {
            background: #ff007f;
            border-radius: 4px;
        }

        .message {
            max-width: 70%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 12px;
            line-height: 1.4;
            word-wrap: break-word;
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
        }

        .message.incoming {
            background: #1a1a1a;
            color: #ffffff;
            border: 1px solid #ffb6c1;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
        }

        .message.outgoing {
            background: linear-gradient(135deg, #ff007f, #ff66b2);
            color: #ffffff;
            border: 1px solid #ffffff;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }

        .sticker-bubble {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 4px !important;
        }

        .sticker-img {
            max-width: 130px;
            border-radius: 10px;
            border: 2px dashed #ff007f;
            background: #222;
        }

        .chat-input-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: #111111;
            border-top: 1px solid #ff007f;
            z-index: 3;
            flex-shrink: 0;
        }

        .chat-input-bar input {
            flex-grow: 1;
            background: #000000;
            border: 1px solid #ffb6c1;
            border-radius: 20px;
            padding: 10px 16px;
            color: #ffffff;
            font-family: 'Comic Sans MS', sans-serif;
            font-size: 12px;
            outline: none;
            box-shadow: inset 0 0 5px rgba(255, 0, 127, 0.3);
        }

        .chat-input-bar input::placeholder {
            color: #ffb6c1;
            opacity: 0.7;
        }

        .send-btn {
            background: linear-gradient(90deg, #ff007f, #ff66b2);
            border: 1px solid #ffffff;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(255, 0, 127, 0.6);
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .send-btn svg {
            width: 16px;
            height: 16px;
            fill: #ffffff;
        }

        .send-btn:hover {
            transform: scale(1.1);
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
    </style>
</head>
<body>

    <div class="fullscreen-chat">
       
        <div class="chat-header">
            <div class="chat-header-left">
                <a href="mensajes.php" class="back-btn" title="Volver">◀</a>
                <img src="<?php echo htmlspecialchars($contacto['avatar']); ?>" alt="Avatar" class="chat-header-avatar" onerror="this.src='../img/perfil.jfif';">
                <span class="chat-header-name"><?php echo htmlspecialchars($contacto['nombre_contacto']); ?></span>
            </div>
        </div>

        
        <div class="chat-body" id="chatBody">
            <?php foreach ($contacto['mensajes'] as$msg): ?>
                <?php if (isset($msg['tipo_contenido']) &&$msg['tipo_contenido'] === 'sticker'): ?>
                    <div class="message <?php echo $msg['tipo']; ?> sticker-bubble">
                        <img src="../img/sticker.gif" alt="Sticker" class="sticker-img" onerror="this.src='../img/match.jfif';">
                    </div>
                <?php else: ?>
                    <div class="message <?php echo $msg['tipo']; ?>">
                        <?php echo htmlspecialchars($msg['texto']); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

      
        <div class="chat-input-bar">
            <input type="text" id="messageInput" placeholder="Escribe un mensaje kawaii...">
            <button class="send-btn" id="sendBtn" title="Enviar">
                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </div>

    </div>


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
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const chatBody = document.getElementById('chatBody');

        function sendMessage() {
            const text = messageInput.value.trim();
            if (text !== "") {
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message outgoing';
                msgDiv.textContent = text;
                chatBody.appendChild(msgDiv);
                messageInput.value = "";
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }

        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        chatBody.scrollTop = chatBody.scrollHeight;

        
        const Y2K_ITEMS = [
            `<svg viewBox="0 0 24 24" fill="#ff007f"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ffb6c1"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ff007f"><path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5Z"/></svg>`,
            `<svg viewBox="0 0 24 24" fill="#ffffff" stroke="#ff007f" stroke-width="1.5"><path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5Z"/></svg>`
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
        setInterval(createFallingItem, 350);

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
            sparkle.style.left = (x + (Math.random() - 0.5) * 15) + 'px';
            sparkle.style.top = (y + (Math.random() - 0.5) * 15) + 'px';
            sparkle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            const size = Math.random() * 8 + 8;
            sparkle.style.width = size + 'px';
            sparkle.style.height = size + 'px';
            document.body.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 800);
        }
    </script>
</body>
</html>