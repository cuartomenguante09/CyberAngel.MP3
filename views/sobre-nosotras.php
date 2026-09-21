<?php
$host = "localhost";
$dbname = "cyber_angel_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {$pdo = null;
}

$usuario = ['nombre' => 'cyber_angel'];
if ($pdo) {
    $query =$pdo->query("SELECT * FROM usuarios LIMIT 1");
    $resultado =$query->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $usuario =$resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Angel - Sobre Nosotras</title>
    
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
        }

        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 48px;
            background: rgba(0, 0, 0, 0.92);
            backdrop-filter: blur(6px);
            border-bottom: 3px solid #ff007f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-sizing: border-box;
            padding: 0 15px;
        }

        .top-nav-left {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Slackey', cursive;
            font-size: 13px;
            color: #ff66b2;
        }

        .top-nav-left img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ff007f;
        }

        .top-nav-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .top-nav-btn {
            background: rgba(255, 0, 127, 0.2);
            border: 1px solid #ff007f;
            color: #ffffff;
            padding: 4px 10px;
            font-size: 11px;
            font-family: 'Slackey', cursive;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.2s;
        }

        .top-nav-btn:hover {
            background: #ff007f;
            color: #000000;
            box-shadow: 0 0 8px #ff007f;
        }

        .about-container {
            width: 100%;
            max-width: 950px;
            padding: 70px 20px 120px 20px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        
        .title-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 15px;
            margin-bottom: 12px;
        }

        .main-title {
            text-align: center;
            font-family: 'Great Vibes', cursive;
            font-size: 58px;
            color: #ffb6c1;
            text-shadow: 2px 2px #ff007f, -2px -2px #000;
            background: rgba(0, 0, 0, 0.85);
            border: 3px solid #ff007f;
            border-radius: 10px;
            padding: 5px 25px;
            box-shadow: 4px 4px 0px #ff007f;
        }

        .intro-box {
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
            max-width: 820px;
            margin: 0 auto 35px auto;
            background: rgba(0, 0, 0, 0.88);
            border: 3px solid #ff007f;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 4px 4px 0px #ff007f;
            text-shadow: 1px 1px 2px #000;
        }

        .intro-box b {
            color: #ff66b2;
            font-size: 13px;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 270px);
            justify-content: center;
            gap: 35px 50px;
            position: relative;
        }

        .polaroid {
            background: #ffffff;
            padding: 8px 8px 22px 8px;
            width: 270px;
            box-shadow: 4px 4px 10px rgba(0,0,0,0.8);
            color: #000000;
            box-sizing: border-box;
            position: relative;
            transition: transform 0.2s ease;
        }

        .polaroid:nth-child(odd) { transform: rotate(-2deg); }
        .polaroid:nth-child(even) { transform: rotate(1.5deg); }
        .polaroid:hover { transform: scale(1.03) rotate(0deg); z-index: 10; }

        .polaroid img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
            display: block;
        }

        .polaroid-info {
            font-size: 10px;
            margin-top: 6px;
            font-family: 'Courier New', monospace;
            line-height: 1.35;
        }

        .song-play-btn {
            background: #ffb6c1;
            border: 1px solid #ff007f;
            color: #ff007f;
            font-size: 9.5px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            padding: 3px 6px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
            display: inline-block;
            transition: background 0.2s;
        }

        .song-play-btn:hover {
            background: #ff007f;
            color: #ffffff;
        }

        .social-tag {
            font-size: 10px;
            font-family: 'Slackey', cursive;
            margin-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .music-player {
            position: fixed;
            bottom: 65px;
            right: 20px;
            background: rgba(255, 192, 203, 0.9);
            border: 2px solid #ff007f;
            border-radius: 12px;
            padding: 10px 15px;
            box-shadow: 0 4px 15px rgba(255, 0, 127, 0.4);
            z-index: 98;
            width: 260px;
            font-family: 'Courier New', monospace;
            backdrop-filter: blur(5px);
            color: #000;
        }

        .player-title {
            font-size: 10px;
            font-weight: bold;
            color: #ff007f;
            text-align: center;
            margin-bottom: 5px;
            font-family: 'Slackey', cursive;
        }

        .track-info {
            font-size: 11px;
            text-align: center;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .player-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .player-btn {
            background: #ff007f;
            border: 1px solid #fff;
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            font-weight: bold;
        }

        .player-btn:hover {
            background: #ff66b2;
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
    </style>
</head>
<body>

    <div class="top-navbar">
        <div class="top-nav-left">
            <img src="../img/perfil.jfif" alt="Avatar">
            <span><?php echo htmlspecialchars($usuario['nombre']); ?></span>
        </div>
        <div class="top-nav-right">
            <a href="home.php" class="top-nav-btn">★ MATCH</a>
            <a href="perfil.php" class="top-nav-btn">PERFIL</a>
        </div>
    </div>

    <div class="about-container">
        
        <div class="title-wrapper">
            <div class="main-title">¿Querés queque?</div>
        </div>
        
        <div class="intro-box">
            <b>Somos Queque Studio. 4 creadoras, 100% queques, 0% bugs.</b><br><br>
            Un equipo de estudiantes apasionadas por la tecnología, la programación y el diseño. Creamos CyberAngelmp3 para romper con las redes sociales aburridas y traer de vuelta la esencia retro de los 2000s: un espacio único, libre de algoritmos raros y lleno de personalidad, música y buenas vibras.<br><br>
            ¡Te servimos la mejor porción de código y estética! ¡Conocé al equipo detrás de la divina magia!
        </div>

        <div class="grid-layout">
            
            <div class="polaroid">
                <img src="../img/sofia.jpg" alt="Sofía Mamani">
                <div class="polaroid-info">
                    <b>Name:</b> Sofía Mamani<br>
                    <b>Age:</b> 16<br>
                    <b>Rol:</b> Programadora Back & Front / Lead Developer<br>
                    <b>I like:</b> leer libros<br>
                    🎵 <b>Mi tema:</b> Fame is a Gun<br>
                    <button class="song-play-btn" onclick="playSpecificSong(0)">▶ Reproducir</button>
                </div>
                <div class="social-tag">
                    <span>@Sooofiiiaaaaaa</span>
                    <span>💖</span>
                </div>
            </div>

            <div class="polaroid">
                <img src="../img/laura.jpg" alt="Laura Farfan">
                <div class="polaroid-info">
                    <b>Name:</b> Laura Farfan<br>
                    <b>Age:</b> 16<br>
                    <b>Rol:</b> Scrum Master & Coordinadora de Procesos.<br>
                    <b>I like:</b> gordos, ser ojo alegre<br>
                    🎵 <b>Mi tema:</b> A Pelo<br>
                    <button class="song-play-btn" onclick="playSpecificSong(2)">▶ Reproducir</button>
                </div>
                <div class="social-tag">
                    <span>@Lauuuraaa</span>
                    <span>💜</span>
                </div>
            </div>

            <div class="polaroid">
                <img src="../img/azumy.jpg" alt="Azumy Salazar">
                <div class="polaroid-info">
                    <b>Name:</b> Azumy Salazar<br>
                    <b>Age:</b> 16<br>
                    <b>Rol:</b> Lead Visual Designer & Frontend Support<br>
                    <b>I like:</b> eee queques, nose, hola, la música a todo volumen.<br>
                    🎵 <b>Mi tema:</b> Generous<br>
                    <button class="song-play-btn" onclick="playSpecificSong(1)">▶ Reproducir</button>
                </div>
                <div class="social-tag">
                    <span>@Azzzuuuuumyyy</span>
                    <span>💚</span>
                </div>
            </div>

            <div class="polaroid">
                <img src="../img/maitena.jpg" alt="Maitena Garcia">
                <div class="polaroid-info">
                    <b>Name:</b> Maitena Garcia<br>
                    <b>Age:</b> 17<br>
                    <b>Rol:</b> Administradora de base de datos<br>
                    <b>I like:</b> franui<br>
                    🎵 <b>Mi tema:</b> Paparazzi<br>
                    <button class="song-play-btn" onclick="playSpecificSong(3)">▶ Reproducir</button>
                </div>
                <div class="social-tag">
                    <span>@Maliiittteee</span>
                    <span>💙</span>
                </div>
            </div>

        </div>

    </div>
    
    <div class="music-player">
        <div class="player-title">Queque Jukebox</div>
        <div class="track-info" id="track-title">Fame is a Gun</div>
        <audio id="audio-element" src="../img/Fame is a Gun.mp3"></audio>
        <div class="player-controls">
            <button class="player-btn" onclick="prevSong()">⏮ Prev</button>
            <button class="player-btn" id="play-pause-btn" onclick="togglePlay()">▶ Play</button>
            <button class="player-btn" onclick="nextSong()">Next ⏭</button>
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
        <button class="nav-item" id="notifications-btn" title="Notificaciones">
            <img src="../img/notificaciones.jfif" alt="Notificaciones">
        </button>
    </div>

    <script>
        const songs = [
            { title: "Fame is a Gun", src: "../img/Fame is a Gun.mp3" },
            { title: "Generous", src: "../img/Generous.mp3" },
            { title: "A Pelo", src: "../img/A Pelo.mp3" },
            { title: "Paparazzi", src: "../img/Paparazzi.mp3" }
        ];

        let currentSongIndex = 0;
        const audioElement = document.getElementById('audio-element');
        const trackTitleElem = document.getElementById('track-title');
        const playPauseBtn = document.getElementById('play-pause-btn');

        function loadSong(index) {
            currentSongIndex = index;
            trackTitleElem.textContent = songs[index].title;
            audioElement.src = songs[index].src;
        }

        function playSpecificSong(index) {
            loadSong(index);
            audioElement.play();
            playPauseBtn.textContent = "⏸ Pause";
        }

        function togglePlay() {
            if (audioElement.paused) {
                audioElement.play();
                playPauseBtn.textContent = "⏸ Pause";
            } else {
                audioElement.pause();
                playPauseBtn.textContent = "▶ Play";
            }
        }

        function nextSong() {
            currentSongIndex = (currentSongIndex + 1) % songs.length;
            loadSong(currentSongIndex);
            audioElement.play();
            playPauseBtn.textContent = "⏸ Pause";
        }

        function prevSong() {
            currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
            loadSong(currentSongIndex);
            audioElement.play();
            playPauseBtn.textContent = "⏸ Pause";
        }

        const profileBtn = document.getElementById('profile-btn');
        profileBtn.addEventListener('contextmenu', e => e.preventDefault());
        profileBtn.addEventListener('mousedown', e => {
            if (e.button === 0) window.location.href = 'home.php';
            else if (e.button === 2) window.location.href = 'perfil.php';
        });

        document.getElementById('match-btn').addEventListener('click', () => {
            window.location.href = 'home.php';
        });

        document.getElementById('add-btn').addEventListener('click', () => {
            window.location.href = 'agregar.php';
        });

        document.getElementById('messages-btn').addEventListener('click', () => {
            window.location.href = 'mensajes.php';
        });

        document.getElementById('notifications-btn').addEventListener('click', () => {
            window.location.href = 'notificaciones.php';
        });

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