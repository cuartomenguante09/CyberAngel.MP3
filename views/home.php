<?php
$host = "localhost";
$dbname = "cyber_angel_db";
$username = "root";
$password = "";

$ultima_pub = null;
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT * FROM publicaciones ORDER BY id DESC LIMIT 1");
    $ultima_pub = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    
}


$imagen_mostrar = ($ultima_pub && !empty($ultima_pub['imagen'])) ? '../img/uploads/' . $ultima_pub['imagen'] : '../img/banner7.jfif';
$caption_mostrar = ($ultima_pub && !empty($ultima_pub['caption'])) ? $ultima_pub['caption'] : 'born to be an absolute iconic diva';
$mood_mostrar = ($ultima_pub && !empty($ultima_pub['mood'])) ? $ultima_pub['mood'] : 'angel';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Angel mp3</title>
    
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
            0% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.2) rotate(0deg);
            }
            100% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.2) translateY(20px) rotate(180deg);
            }
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
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.95;
            }
            100% {
                transform: translateY(105vh) rotate(360deg);
                opacity: 0;
            }
        }

        html {
            overflow-y: scroll;
        }

        ::-webkit-scrollbar {
            width: 16px;
            background-color: #ffe4e1;
        }

        ::-webkit-scrollbar-track {
            background: #ffd0e3;
            border-left: 2px solid #ff007f;
        }

        ::-webkit-scrollbar-thumb {
            background: #ff66b2;
            border: 2px solid #ffffff;
            outline: 1px solid #ff007f;
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ff007f;
        }

        body {
            margin: 0;
            padding: 0;
            background-image: url('../img/fondo.jfif');
            background-repeat: repeat;
            background-size: 600px auto;
            font-family: 'Comic Sans MS', 'Courier New', sans-serif;
            color: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .y2k-desktop {
            width: 100%;
            max-width: 920px;
            padding: 20px 20px 90px 20px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        .header-top-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .top-left-img {
            width: 220px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.6));
            transition: transform 0.2s ease;
        }

        .top-left-img:hover {
            transform: scale(1.03);
        }

        .header-banner {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        .top-logo-right {
            width: 170px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.5));
            transition: transform 0.2s ease;
        }

        .top-logo-right:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .marquee-container {
            width: 100%;
            overflow: hidden;
            background: #ffffff;
            border: 3px solid #ff007f;
            box-shadow: 3px 3px 0px #000000;
            margin-bottom: 20px;
            padding: 6px 0;
            white-space: nowrap;
        }

        .marquee-track {
            display: inline-flex;
            gap: 12px;
            animation: scroll-left 15s linear infinite;
        }

        .marquee-container:hover .marquee-track {
            animation-play-state: paused;
        }

        .marquee-item {
            width: 110px;
            height: 70px;
            object-fit: cover;
            border: 2px solid #ff007f;
            border-radius: 4px;
            box-sizing: border-box;
            flex-shrink: 0;
        }

        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .y2k-grid {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 16px;
            margin: 0 auto;
        }

        .win95-box {
            width: 270px;
            background: #ffb6c1;
            border: 3px solid #ff007f;
            box-shadow: 4px 4px 0px #000000;
        }

        .win95-titlebar {
            background: #ff007f;
            color: #ffffff;
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
        }

        .win95-menu {
            background: #ffc0cb;
            font-size: 11px;
            padding: 3px 6px;
            border-bottom: 1px solid #ff007f;
        }

        .win95-content {
            background: #ffffff;
            padding: 10px;
            text-align: center;
        }

        .win95-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border: 2px solid #ff007f;
            display: block;
        }

        .center-col {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 185px;
        }

        .gender-box {
            background: #ffffff;
            border: 2px solid #ff007f;
            padding: 8px;
            box-shadow: 3px 3px 0px #000000;
        }

        .ipod-widget {
            display: block;
            width: 100%;
        }

        .ipod-widget .ipod {
            width: 100%;
            background: #ff007f;
            border-radius: 18px;
            padding: 10px;
            box-sizing: border-box;
            box-shadow: 3px 3px 0px #000000, inset -2px -2px 4px rgba(0,0,0,0.3);
            text-align: center;
            border: 2px solid #ffffff;
            position: relative;
            overflow: hidden;
        }

        .ipod-widget .youtube-hidden-container {
            width: 1px;
            height: 1px;
            position: absolute;
            bottom: 0;
            left: 0;
            opacity: 0.01;
            overflow: hidden;
        }

        .ipod-widget .screen {
            background: #ffffff;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            border: 2px solid #ffb6c1;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .ipod-widget .screen img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #ff007f;
        }

        .ipod-widget .screen p {
            font-family: 'Slackey', cursive;
            font-size: 11px;
            color: #ff007f;
            margin: 3px 0 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .ipod-widget .time-display {
            font-size: 9px;
            color: #555555;
            font-family: 'Comic Sans MS', sans-serif;
            font-weight: bold;
        }

        .ipod-widget .progress-container {
            width: 100%;
            background: #ffe4e1;
            height: 6px;
            border-radius: 3px;
            margin-top: 2px;
            position: relative;
            border: 1px solid #ff66b2;
            cursor: pointer;
        }

        .ipod-widget .progress-bar {
            background: #ff007f;
            height: 100%;
            width: 0%;
            border-radius: 3px;
        }

        .ipod-widget .controls-wheel {
            position: relative;
            width: 110px;
            height: 110px;
            background: #ffffff;
            border-radius: 50%;
            margin: 12px auto 4px auto;
            border: 2px solid #ffffff;
            box-shadow: 2px 2px 0px #000000;
        }

        .ipod-widget .ipod-btn {
            position: absolute;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.1s;
        }

        .ipod-widget .ipod-btn:active {
            transform: scale(0.85);
        }

        .ipod-widget .btn-play-pause {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #ff007f;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 2;
        }

        .ipod-widget .btn-play-pause:active {
            transform: translate(-50%, -50%) scale(0.9);
        }

        .ipod-widget .play-icon {
            display: block;
            transition: all 0.1s ease;
        }

        .ipod-widget .state-paused .play-icon {
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            border-left: 11px solid #ffffff;
            margin-left: 3px;
        }

        .ipod-widget .state-playing .play-icon {
            width: 10px;
            height: 12px;
            border-left: 3px solid #ffffff;
            border-right: 3px solid #ffffff;
            box-sizing: border-box;
        }

        .ipod-widget .btn-prev {
            top: 50%;
            left: 8px;
            transform: translateY(-50%);
        }
        .ipod-widget .icon-prev {
            display: block;
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-right: 8px solid #ff007f;
            position: relative;
        }
        .ipod-widget .icon-prev::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 8px;
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-right: 8px solid #ff007f;
        }

        .ipod-widget .btn-next {
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
        }
        .ipod-widget .icon-next {
            display: block;
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-left: 8px solid #ff007f;
            position: relative;
        }
        .ipod-widget .icon-next::after {
            content: '';
            position: absolute;
            top: -5px;
            right: 8px;
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-left: 8px solid #ff007f;
        }

        .right-col {
            display: flex;
            background: #ffc0cb;
            border: 3px solid #ff007f;
            padding: 10px;
            gap: 10px;
            box-shadow: 4px 4px 0px #000000;
        }

        .gallery {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 150px;
        }

        .gallery-img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-sizing: border-box;
        }

        .quote-card {
            background: #ffffff;
            border: 1px solid #ff007f;
            padding: 6px;
            font-size: 11px;
            text-align: center;
            font-weight: bold;
            color: #ff007f;
        }

        .mlas-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
        }

        .mlas-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: #ffffff;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 2px 2px 0px #000000;
            border: 1px solid #fff;
        }

        .btn-m { background: #1b2a78; }
        .btn-l { background: #2f1d5a; }
        .btn-a { background: #88e344; }
        .btn-s { background: #96356c; }

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

       
        .crud-corner-btn {
            position: fixed;
            bottom: 65px;
            left: 15px;
            background: #ffb6c1;
            border: 3px solid #ff007f;
            box-shadow: 4px 4px 0px #000000;
            z-index: 98;
            width: 90px;
            text-align: center;
        }

        .crud-corner-titlebar {
            background: #ff007f;
            color: #ffffff;
            padding: 2px 4px;
            font-size: 9px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }

        .crud-corner-body {
            padding: 6px;
            background: #ffffff;
        }

        .crud-corner-link {
            display: block;
            background: #ff007f;
            color: #ffffff;
            text-decoration: none;
            font-family: 'Slackey', cursive;
            font-size: 11px;
            padding: 4px 0;
            border: 2px solid #ffffff;
            box-shadow: 2px 2px 0px #000000;
            transition: transform 0.1s ease;
        }

        .crud-corner-link:hover {
            background: #ff66b2;
            transform: scale(1.05);
        }

        .crud-corner-link:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>

    <div class="y2k-desktop">
        
        <div class="header-top-container">
            <img src="../img/cyberangel.png" alt="Cyberangel" class="top-left-img">
            
            <div class="header-banner">
                <a href="http://www.gigaglitters.com/es/" target="_blank">
                    <img src="http://www.gigaglitters.com/es/creados/Fl9lgmdog9.gif" width="387" height="93" border="0" alt="Glitter Graphics">
                </a>
                <br>
                <a href="http://www.gigaglitters.com/es/" target="_blank" style="color: #ff007f; font-size: 11px; text-decoration: none; font-weight: bold;">
                    http://www.gigaglitters.com/es/ - Glitter Graphics
                </a>
            </div>

            <img src="../img/logo.png" alt="Cyber Angel Logo" class="top-logo-right">
        </div>

        <div class="marquee-container">
            <div class="marquee-track">
                <img src="../img/banner1.jfif" class="marquee-item" alt="Banner 1">
                <img src="../img/banner2.jfif" class="marquee-item" alt="Banner 2">
                <img src="../img/banner3.gif" class="marquee-item" alt="Banner 3">
                <img src="../img/banner4.gif" class="marquee-item" alt="Banner 4">
                <img src="../img/banner5.gif" class="marquee-item" alt="Banner 5">
                <img src="../img/banner6.jfif" class="marquee-item" alt="Banner 6">

                <img src="../img/banner1.jfif" class="marquee-item" alt="Banner 1">
                <img src="../img/banner2.jfif" class="marquee-item" alt="Banner 2">
                <img src="../img/banner3.gif" class="marquee-item" alt="Banner 3">
                <img src="../img/banner4.gif" class="marquee-item" alt="Banner 4">
                <img src="../img/banner5.gif" class="marquee-item" alt="Banner 5">
                <img src="../img/banner6.jfif" class="marquee-item" alt="Banner 6">
            </div>
        </div>

        <div class="y2k-grid">
            
            <div class="win95-box">
                <div class="win95-titlebar">
                    <span>Angel.exe</span>
                    <span>— ❐ ✕</span>
                </div>
                <div class="win95-menu">
                    <u>F</u>ile &nbsp; <u>E</u>dit &nbsp; <u>V</u>iew &nbsp; <u>H</u>elp
                </div>
                <div class="win95-content">
                    <img src="<?php echo htmlspecialchars($imagen_mostrar); ?>" class="win95-img" alt="Última Publicación">
                    <div style="margin-top: 6px; font-size: 10px; color: #ff007f; word-break: break-word;">
                        ✨ <b><?php echo htmlspecialchars($caption_mostrar); ?></b>
                    </div>
                </div>
            </div>

            <div class="center-col">
                <div class="gender-box">
                    <label style="font-size: 13px;"><b>Gender:</b></label><br>
                    <select style="width: 100%; margin-top: 5px; padding: 2px;">
                        <option>Female</option>
                        <option>Male</option>
                        <option selected>Angel</option>
                    </select>
                </div>

                <div class="ipod-widget">
                  <div class="ipod">
                    <div class="screen">
                      <img id="cover" src="" alt="cover">
                      <p id="track-title">Cargando...</p>
                      
                      <div class="progress-container" id="progress-container">
                        <div class="progress-bar" id="progress-bar"></div>
                      </div>
                      <div class="time-display">
                        <span id="current-time">0:00</span> / <span id="duration-time">0:00</span>
                      </div>
                    </div>

                    <div class="controls-wheel">
                      <button id="play" class="ipod-btn btn-play-pause state-paused" title="Play/Pause">
                        <span class="play-icon"></span>
                      </button>
                      <button id="prev" class="ipod-btn btn-prev" title="Anterior">
                        <span class="icon-prev"></span>
                      </button>
                      <button id="next" class="ipod-btn btn-next" title="Siguiente">
                        <span class="icon-next"></span>
                      </button>
                    </div>

                    <div class="youtube-hidden-container">
                      <div id="player"></div>
                    </div>
                  </div>
                </div>

            </div>

            <div class="right-col">
                <div class="gallery">
                    <img src="../img/banner9.jfif" class="gallery-img" alt="Iconic">
                    <img src="../img/banner10.jfif" class="gallery-img" alt="2009">
                    <div class="quote-card">
                        born to be an absolute iconic diva
                    </div>
                </div>

                <div class="mlas-buttons">
                    <div class="mlas-btn btn-m">M</div>
                    <div class="mlas-btn btn-l">L</div>
                    <div class="mlas-btn btn-a">A</div>
                    <div class="mlas-btn btn-s">S</div>
                </div>
            </div>

        </div>

    </div>

    
    <div class="crud-corner-btn">
        <div class="crud-corner-titlebar">
            <span>db.exe</span>
            <span>×</span>
        </div>
        <div class="crud-corner-body">
            <a href="crud.php" class="crud-corner-link">CRUD</a>
        </div>
    </div>

    <div class="bottom-bar">
        <button class="nav-item" id="profile-btn" title="Clic izq: Sobre Nosotras | Clic der: Perfil">
            <img src="../img/perfil.jfif" alt="Perfil">
        </button>
        <button class="nav-item" title="Match" onclick="window.location.href='match.php'">
            <img src="../img/match.jfif" alt="Match">
        </button>
        <button class="nav-item" title="Agregar" onclick="window.location.href='agregar.php'">
            <img src="../img/simbolomas.jfif" alt="Agregar">
        </button>
        <button class="nav-item" title="Mensajes" onclick="window.location.href='mensajes.php'">
            <img src="../img/mensajes.jfif" alt="Mensajes">
        </button>
        <button class="nav-item" title="Notificaciones" onclick="window.location.href='notificaciones.php'">
            <img src="../img/notificaciones.jfif" alt="Notificaciones">
        </button>
    </div>

    <script>
    const profileBtn = document.getElementById('profile-btn');

    profileBtn.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    profileBtn.addEventListener('mousedown', function(e) {
        if (e.button === 0) {
            window.location.href = 'sobre-nosotras.php';
        } else if (e.button === 2) {
            window.location.href = 'perfil.php';
        }
    });

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

    let player;
    let current = 0;
    let progressInterval;
    let apiReady = false;

    const playlist = [
      {
        title: "Christina Aguilera - Genie in a Bottle",
        id: "kIDWG7Xar4E",
        cover: "../img/cancion.jfif"
      },
      {
        title: "Britney Spears - Toxic",
        id: "LOZuxwoc7B8",
        cover: "../img/banner1.jfif"
      },
      {
        title: "Katy Perry - Teenage Dream",
        id: "98WtmW-lfeE",
        cover: "../img/banner2.jfif"
      }
    ];

    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function onYouTubeIframeAPIReady() {
      player = new YT.Player("player", {
        height: "1",
        width: "1",
        videoId: playlist[current].id,
        playerVars: {
          autoplay: 0,
          controls: 0,
          disablekb: 1,
          fs: 0,
          rel: 0,
          modestbranding: 1,
          playlist: playlist[current].id
        },
        events: {
          'onStateChange': onPlayerStateChange,
          'onReady': onPlayerReady
        }
      });
    }

    function onPlayerReady() {
      apiReady = true;
      updateUI();
    }

    function updateUI() {
      document.getElementById("track-title").textContent = playlist[current].title;
      document.getElementById("cover").src = playlist[current].cover;
    }

    function onPlayerStateChange(event) {
      const btnPlay = document.getElementById("play");
      
      if (event.data === YT.PlayerState.PLAYING) {
        btnPlay.classList.remove("state-paused");
        btnPlay.classList.add("state-playing");
        startProgressTimer();
      } else {
        btnPlay.classList.remove("state-playing");
        btnPlay.classList.add("state-paused");
        clearInterval(progressInterval);
      }

      if (event.data === YT.PlayerState.ENDED) {
        nextTrack();
      }
    }

    document.getElementById("play").onclick = () => {
      if (!apiReady || !player) return;
      
      const state = player.getPlayerState();
      if (state !== YT.PlayerState.PLAYING) {
        player.playVideo();
      } else {
        player.pauseVideo();
      }
    };

    function nextTrack() {
      if (!player || !apiReady) return;
      current = (current + 1) % playlist.length;
      player.loadVideoById(playlist[current].id);
      updateUI();
    }
    document.getElementById("next").onclick = nextTrack;

    document.getElementById("prev").onclick = () => {
      if (!player || !apiReady) return;
      current = (current - 1 + playlist.length) % playlist.length;
      player.loadVideoById(playlist[current].id);
      updateUI();
    };

    function startProgressTimer() {
      clearInterval(progressInterval);
      progressInterval = setInterval(() => {
        if (player && player.getCurrentTime) {
          const currentTime = player.getCurrentTime();
          const duration = player.getDuration();
          
          if (duration > 0) {
            const percentage = (currentTime / duration) * 100;
            document.getElementById("progress-bar").style.width = percentage + "%";
            document.getElementById("current-time").textContent = formatTime(currentTime);
            document.getElementById("duration-time").textContent = formatTime(duration);
          }
        }
      }, 500);
    }

    function formatTime(time) {
      const mins = Math.floor(time / 60);
      const secs = Math.floor(time % 60);
      return mins + ":" + (secs < 10 ? "0" : "") + secs;
    }

    document.getElementById("progress-container").onclick = function(e) {
      if (player && player.getDuration && apiReady) {
        const rect = this.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const width = rect.width;
        const duration = player.getDuration();
        
        if (duration > 0) {
          const newTime = (clickX / width) * duration;
          player.seekTo(newTime, true);
        }
      }
    };

    window.onload = function() {
      if(apiReady) updateUI();
    };
    </script>
</body>
</html>