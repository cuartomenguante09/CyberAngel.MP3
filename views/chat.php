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
            ['tipo' => 'incoming', 'tipo_contenido' => 'sticker', 'ruta' => '../img/sticker3.png']
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
    } catch (PDOException $e) {}
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
            background: transparent;
            position: relative;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            z-index: 2;
            transition: background 0.3s ease;
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

        .chat-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .customize-toggle-btn {
            background: #111;
            border: 2px solid #ffb6c1;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 0 6px rgba(255, 182, 193, 0.4);
            transition: transform 0.2s;
        }

        .customize-toggle-btn:hover {
            transform: scale(1.1);
            border-color: #ff007f;
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

        /* Ajuste fino de paddings para que el texto quede centrado dentro del globo */
        .message {
            max-width: 80%;
            min-width: 140px;
            min-height: 60px;
            padding: 14px 20px 24px 20px;
            font-size: 12px;
            line-height: 1.4;
            word-wrap: break-word;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            box-shadow: none !important;
            border: none !important;
            color: #000000;
            font-weight: bold;
        }

        .message.incoming {
            background-image: url('../img/globo1izquierda.png');
            align-self: flex-start;
            padding: 14px 22px 24px 26px;
        }

        .message.outgoing {
            background-image: url('../img/globo1derecha.png');
            align-self: flex-end;
            padding: 14px 26px 24px 22px;
        }

        .sticker-bubble {
            background: transparent !important;
            background-image: none !important;
            border: none !important;
            box-shadow: none !important;
            padding: 4px !important;
            min-width: auto !important;
            min-height: auto !important;
        }

        .sticker-img {
            max-width: 130px;
            border-radius: 10px;
            display: block;
        }

        .emoji-container {
            display: inline-flex;
            vertical-align: middle;
            margin: 0 1px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #ffffff;
            overflow: hidden;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 182, 193, 0.5);
            box-sizing: border-box;
        }

        .chat-inline-emoji {
            width: 28px;
            height: 28px;
            object-fit: cover;
            object-position: center;
            mix-blend-mode: multiply;
        }

        /* Panel de Personalización */
        .bg-panel {
            position: absolute;
            top: 60px;
            right: 20px;
            width: 320px;
            background-color: #000000;
            border: 3px solid #ffb6c1;
            box-shadow: 0 0 20px rgba(255, 182, 193, 0.6);
            font-family: 'Slackey', cursive, sans-serif;
            padding: 12px;
            z-index: 10;
            display: none;
            box-sizing: border-box;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .bg-panel.active {
            display: block;
        }

        .bg-subtitle {
            color: #ffb6c1;
            font-size: 9px;
            margin: 10px 0 6px 0;
        }

        .bg-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .bg-option-card {
            background-color: #111;
            border: 2px solid #ffb6c1;
            border-radius: 10px;
            height: 75px;
            cursor: pointer;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            transition: transform 0.2s, border-color 0.2s;
        }

        .bg-option-card:hover {
            transform: scale(1.05);
            border-color: #ff007f;
        }

        .emoji-picker-panel, .sticker-picker-panel {
            position: absolute;
            bottom: 70px;
            left: 20px;
            width: 310px;
            background-color: #e5e5e5;
            border: 3px solid #ff007f;
            box-shadow: 0 0 20px rgba(255, 0, 127, 0.5);
            font-family: 'Slackey', cursive, sans-serif;
            padding: 10px;
            z-index: 10;
            display: none;
            box-sizing: border-box;
            border-radius: 8px;
        }

        .emoji-picker-panel.active, .sticker-picker-panel.active {
            display: block;
        }

        .panel-header {
            background-color: #ffb6c1;
            color: #d64573;
            font-size: 10px;
            padding: 6px;
            text-align: center;
            letter-spacing: 1px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .emoji-grid, .sticker-grid {
            display: grid;
            gap: 8px;
            max-height: 180px;
            overflow-y: auto;
            padding: 4px;
            background-color: #ffffff;
            border-radius: 6px;
        }

        .emoji-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .sticker-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .emoji-item, .sticker-item {
            background-color: transparent;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.15s ease;
        }

        .emoji-item { height: 42px; }
        .sticker-item { height: 85px; padding: 2px; }

        .emoji-item img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .sticker-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .emoji-item:hover, .sticker-item:hover {
            transform: scale(1.1);
        }

        .chat-input-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: #111111;
            border-top: 1px solid #ff007f;
            z-index: 3;
            flex-shrink: 0;
        }

        .action-btn-input {
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .action-btn-input:hover {
            transform: scale(1.2);
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

    <div class="fullscreen-chat" id="fullscreenChat">
        
        <div class="chat-header">
            <div class="chat-header-left">
                <a href="mensajes.php" class="back-btn" title="Volver">◀</a>
                <img src="<?php echo htmlspecialchars($contacto['avatar']); ?>" alt="Avatar" class="chat-header-avatar" onerror="this.src='../img/perfil.jfif';">
                <span class="chat-header-name"><?php echo htmlspecialchars($contacto['nombre_contacto']); ?></span>
            </div>
            <div class="chat-header-right">
                <button class="customize-toggle-btn" id="toggleBgBtn" title="Personalizar chat">🎨</button>
            </div>
        </div>

        
        <div class="chat-body" id="chatBody">
            <?php foreach ($contacto['mensajes'] as$msg): ?>
                <?php if (isset($msg['tipo_contenido']) &&$msg['tipo_contenido'] === 'sticker'): ?>
                    <div class="message <?php echo $msg['tipo']; ?> sticker-bubble">
                        <img src="<?php echo htmlspecialchars($msg['ruta'] ?? '../img/sticker3.png'); ?>" alt="Sticker" class="sticker-img">
                    </div>
                <?php else: ?>
                    <div class="message <?php echo $msg['tipo']; ?>">
                        <?php echo htmlspecialchars($msg['texto']); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        
        <div class="bg-panel" id="bgPanel">
            <div class="panel-header">Personaliza tus chats mor!</div>
            
            <div class="bg-subtitle">Fondos de chat inspos !;</div>
            <div class="bg-grid">
                <div class="bg-option-card" style="background-image: url('../img/fondito1.jpg');" onclick="changeChatBackground('url(\'../img/fondito1.jpg\')')"></div>
                <div class="bg-option-card" style="background-image: url('../img/fondito2.jpg');" onclick="changeChatBackground('url(\'../img/fondito2.jpg\')')"></div>
                <div class="bg-option-card" style="background-image: url('../img/fondito3.jpg');" onclick="changeChatBackground('url(\'../img/fondito3.jpg\')')"></div>
                <div class="bg-option-card" style="background-image: url('../img/fondito4.jpg');" onclick="changeChatBackground('url(\'../img/fondito4.jpg\')')"></div>
            </div>

            <div class="bg-subtitle">Globo de texto inspos !;</div>
            <div class="bg-grid">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <div class="bg-option-card" style="background-image: url('../img/previa<?php echo $i; ?>.jpg');" onclick="changeBubbleDesign(<?php echo $i; ?>)"></div>
                <?php endfor; ?>
            </div>
        </div>

        
        <div class="emoji-picker-panel" id="emojiPanel">
            <div class="panel-header">DEFAULT ICONIC EMOJIS !</div>
            <div class="emoji-grid">
                <?php for ($i = 1; $i <= 28; $i++): ?>
                    <div class="emoji-item" data-id="<?php echo $i; ?>">
                        <img src="../img/emoji<?php echo $i; ?>.jpg" alt="Emoji <?php echo $i; ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        
        <div class="sticker-picker-panel" id="stickerPanel">
            <div class="panel-header">KAWAII STICKERS !</div>
            <div class="sticker-grid">
                <?php for ($s = 1; $s <= 4; $s++): ?>
                    <div class="sticker-item" data-src="../img/sticker<?php echo $s; ?>.png">
                        <img src="../img/sticker<?php echo $s; ?>.png" alt="Sticker <?php echo $s; ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        
        <div class="chat-input-bar">
            <button class="action-btn-input" id="toggleStickerBtn" title="Stickers">⭐</button>
            <button class="action-btn-input" id="toggleEmojiBtn" title="Emojis">😊</button>
            <input type="text" id="messageInput" placeholder="escribe un mensaje bonito <3">
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
        const toggleBgBtn = document.getElementById('toggleBgBtn');
        const bgPanel = document.getElementById('bgPanel');
        const toggleEmojiBtn = document.getElementById('toggleEmojiBtn');
        const emojiPanel = document.getElementById('emojiPanel');
        const toggleStickerBtn = document.getElementById('toggleStickerBtn');
        const stickerPanel = document.getElementById('stickerPanel');
        const fullscreenChat = document.getElementById('fullscreenChat');

        function parseMessageContent(text) {
            const escaped = text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
            return escaped.replace(/\[emoji(\d+)\]/g, function(match, id) {
                return `<span class="emoji-container"><img src="../img/emoji${id}.jpg" class="chat-inline-emoji" alt="emoji"></span>`;
            });
        }

        function sendMessage() {
            const rawText = messageInput.value.trim();
            if (rawText !== "") {
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message outgoing';
                msgDiv.innerHTML = parseMessageContent(rawText);
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

        toggleBgBtn.addEventListener('click', function() {
            bgPanel.classList.toggle('active');
            emojiPanel.classList.remove('active');
            stickerPanel.classList.remove('active');
        });

        toggleEmojiBtn.addEventListener('click', function() {
            emojiPanel.classList.toggle('active');
            bgPanel.classList.remove('active');
            stickerPanel.classList.remove('active');
        });

        toggleStickerBtn.addEventListener('click', function() {
            stickerPanel.classList.toggle('active');
            bgPanel.classList.remove('active');
            emojiPanel.classList.remove('active');
        });

        document.querySelectorAll('.emoji-item').forEach(item => {
            item.addEventListener('click', function() {
                const emojiId = this.getAttribute('data-id');
                messageInput.value += `[emoji${emojiId}]`;
                messageInput.focus();
            });
        });

        document.querySelectorAll('.sticker-item').forEach(item => {
            item.addEventListener('click', function() {
                const src = this.getAttribute('data-src');
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message outgoing sticker-bubble';

                const imgEl = document.createElement('img');
                imgEl.src = src;
                imgEl.className = 'sticker-img';

                msgDiv.appendChild(imgEl);
                chatBody.appendChild(msgDiv);
                chatBody.scrollTop = chatBody.scrollHeight;

                stickerPanel.classList.remove('active');
            });
        });

        function changeChatBackground(bgStyle) {
            fullscreenChat.style.background = bgStyle;
            fullscreenChat.style.backgroundSize = 'cover';
            fullscreenChat.style.backgroundPosition = 'center';
            fullscreenChat.style.backgroundRepeat = 'no-repeat';
            bgPanel.classList.remove('active');
        }

        function changeBubbleDesign(id) {
            const incomingMsgs = document.querySelectorAll('.message.incoming:not(.sticker-bubble)');
            const outgoingMsgs = document.querySelectorAll('.message.outgoing:not(.sticker-bubble)');

            incomingMsgs.forEach(msg => {
                msg.style.backgroundImage = `url('../img/globo${id}izquierda.png')`;
            });

            outgoingMsgs.forEach(msg => {
                msg.style.backgroundImage = `url('../img/globo${id}derecha.png')`;
            });

            bgPanel.classList.remove('active');
        }

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