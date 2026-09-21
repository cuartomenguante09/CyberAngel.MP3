<?php
$host = "localhost";
$dbname = "cyber_angel_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {$pdo = null;
}

$mensaje_exito = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&$pdo) {
    $caption =$_POST['caption'] ?? '';
    $mood =$_POST['mood'] ?? 'chilling';
    $imagen_base64 =$_POST['imagen_base64'] ?? '';
    
    if (!empty($imagen_base64)) {
        list($type, $imagen_base64) = explode(';',$imagen_base64);
        list(, $imagen_base64)      = explode(',', $imagen_base64);$imageDecoded = base64_decode($imagen_base64);$newFileName = md5(time() . rand()) . '.jpg';
        $uploadFileDir = '../img/uploads/';
        
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }
        
        $dest_path = $uploadFileDir .$newFileName;
        
        if (file_put_contents($dest_path,$imageDecoded)) {
            try {
                $stmt =$pdo->prepare("INSERT INTO publicaciones (imagen, caption, mood) VALUES (?, ?, ?)");
                $stmt->execute([$newFileName,$caption, $mood]);$mensaje_exito = "¡Publicación subida con éxito!";
            } catch (Exception $e) {$mensaje_exito = "¡Imagen guardada con éxito!";
            }
        }
    }
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
    <title>Cyber Angel - Añadir Publicación</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    
    <style>
        *, body, a, button, select, input, textarea {
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
            background-color: #000000;
            font-family: 'Courier New', monospace;
            color: #ffffff;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000;
            overflow: hidden;
        }

        .app-container::after {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%);
            background-size: 100% 4px;
            z-index: 50;
            pointer-events: none;
            opacity: 0.5;
        }

        .side-bar-left, .side-bar-right {
            position: absolute;
            top: 0;
            width: 15vw;
            max-width: 180px;
            height: 100%;
            background-image: url('../img/animal.jfif');
            background-repeat: repeat;
            background-size: cover;
            z-index: 10;
        }
        .side-bar-left { left: 0; }
        .side-bar-right { right: 0; }

        .top-left-title {
            position: absolute;
            top: 20px;
            left: 17vw;
            font-family: 'Great Vibes', cursive;
            font-size: 26px;
            color: #ff99cc;
            line-height: 1.1;
            text-shadow: 1px 1px #ff007f;
            z-index: 20;
            pointer-events: none;
            letter-spacing: 1px;
        }

        .workspace-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 20;
        }

        .workspace-center {
            position: relative;
            width: 440px;
            height: 260px;
            border: 2px solid #ff007f;
            background: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 15px rgba(255, 0, 127, 0.4);
        }

        .preview-box {
            width: 400px;
            height: 220px;
            border: 1px solid #ff007f;
            background: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        .preview-box img {
            max-width: 100%;
            display: block;
        }

        .preview-placeholder {
            color: #ff99cc;
            font-size: 13px;
            text-align: center;
            font-family: 'Courier New', monospace;
        }

        .corner-heart {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #ff007f;
            background: url('../img/corazoncito.jfif') no-repeat center center;
            background-size: cover;
            box-shadow: 0 0 10px #ff007f;
            z-index: 25;
            overflow: hidden;
        }

        .post-details {
            width: 440px;
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .caption-input {
            width: 100%;
            background: #111;
            border: 1px solid #ff007f;
            color: #ff99cc;
            padding: 6px 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            box-sizing: border-box;
            outline: none;
            box-shadow: inset 0 0 5px rgba(255,0,127,0.3);
        }

        .caption-input::placeholder {
            color: #a84c7a;
        }

        .mood-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: #ff99cc;
            background: #111;
            border: 1px solid #ff007f;
            padding: 4px 10px;
        }

        .mood-select {
            background: #000;
            color: #ff99cc;
            border: 1px solid #ff007f;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            padding: 2px 5px;
            outline: none;
        }

        .right-controls {
            position: absolute;
            right: 17vw;
            height: 80%;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            z-index: 25;
            width: 140px;
        }

        .upload-icon-btn {
            width: 65px;
            height: 65px;
            background: url('../img/photo.jpg') no-repeat center center;
            background-size: cover;
            border: 2px solid #ff007f;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(255,0,127,0.6);
            transition: transform 0.2s;
        }

        .upload-icon-btn:hover {
            transform: scale(1.1);
        }

        .queen-publish-btn {
            width: 80px;
            height: 80px;
            background: url('../img/queen.jpg') no-repeat center center;
            background-size: cover;
            border: 2px dashed #ff007f;
            border-radius: 50%;
            background-color: #000;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 0 10px rgba(255,0,127,0.4);
        }

        .queen-publish-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 0 15px #ff007f;
        }

        .close-cross-btn {
            width: 65px;
            height: 65px;
            background: url('../img/cruz.jpg') no-repeat center center;
            background-size: cover;
            border: 2px solid #ff007f;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(255,0,127,0.5);
            transition: transform 0.2s;
        }

        .close-cross-btn:hover {
            transform: scale(1.15);
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

    <div class="app-container">
        
        <div class="side-bar-left"></div>
        <div class="side-bar-right"></div>

        <div class="top-left-title">Cyber<br>Angel<br>.mp3</div>

        <form action="agregar.php" method="POST" enctype="multipart/form-data" id="postForm" class="workspace-wrapper">
            <div class="workspace-center">
                <div class="corner-heart"></div>
                
                <div class="preview-box" id="previewBox">
                    <span class="preview-placeholder" id="placeholderText">Sube una imagen<br>para tu publicación</span>
                    <img id="imageToCrop" style="display: none;" alt="A recortar">
                </div>
            </div>

            <div class="post-details">
                <input type="text" name="caption" class="caption-input" id="postCaption" placeholder="~* Escribe tu estado o caption aquí... *~">
                
                <div class="mood-container">
                    <span>✦ Mood:</span>
                    <select name="mood" class="mood-select" id="postMood">
                        <option value="chilling">🖤 Chilling / Relax</option>
                        <option value="flawless">✨ Flawless & Cute</option>
                        <option value="bored">💤 Bored / Whatever</option>
                        <option value="lovesick">💖 Lovesick</option>
                        <option value="cyber">🎧 Cyber & Dark</option>
                    </select>
                </div>
            </div>

            <input type="file" id="fileInput" accept="image/*" style="display: none;" onchange="initCropper(event)">
        </form>

        <div class="right-controls">
            <button type="button" class="upload-icon-btn" onclick="document.getElementById('fileInput').click()" title="Subir foto"></button>
            <button type="button" class="queen-publish-btn" onclick="publishPost()" title="Publicar"></button>
            <button type="button" class="close-cross-btn" onclick="window.location.href='home.php'" title="Cancelar"></button>
        </div>

        <div class="bottom-bar">
            <button class="nav-item" id="profile-btn" title="Perfil">
                <img src="../img/perfil.jfif" alt="Perfil">
            </button>
            <button class="nav-item" id="match-btn" title="Match">
                <img src="../img/match.jfif" alt="Match">
            </button>
            <button class="nav-item active" id="add-btn" title="Agregar">
                <img src="../img/simbolomas.jfif" alt="Agregar">
            </button>
            <button class="nav-item" id="messages-btn" title="Mensajes">
                <img src="../img/mensajes.jfif" alt="Mensajes">
            </button>
            <button class="nav-item" id="notifications-btn" title="Notificaciones">
                <img src="../img/notificaciones.jfif" alt="Notificaciones">
            </button>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        <?php if(!empty($mensaje_exito)): ?>
            alert("<?php echo $mensaje_exito; ?>");
            window.location.href = 'home.php';
        <?php endif; ?>

        let cropper = null;

        function initCropper(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageElem = document.getElementById('imageToCrop');
                    const placeholder = document.getElementById('placeholderText');
                    
                    if (placeholder) placeholder.style.display = 'none';
                    
                    imageElem.src = e.target.result;
                    imageElem.style.display = 'block';

                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(imageElem, {
                        aspectRatio: NaN,
                        viewMode: 1,
                        autoCropArea: 0.9,
                        responsive: true,
                    });
                }
                reader.readAsDataURL(file);
            }
        }

        function publishPost() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 800,
                    height: 800,
                });
                const base64Image = canvas.toDataURL('image/jpeg');
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'imagen_base64';
                hiddenInput.value = base64Image;
                
                const form = document.getElementById('postForm');
                form.appendChild(hiddenInput);
                form.submit();
            } else {
                alert("⚠️ ¡Haz clic primero en el icono de foto para seleccionar y ajustar una imagen!");
            }
        }

        const profileBtn = document.getElementById('profile-btn');
        if (profileBtn) {
            profileBtn.addEventListener('contextmenu', function(e) { e.preventDefault(); });
            profileBtn.addEventListener('mousedown', function(e) {
                if (e.button === 0) {
                    window.location.href = 'home.php'; 
                } else if (e.button === 2) {
                    window.location.href = 'perfil.php';
                }
            });
        }

        document.getElementById('match-btn').addEventListener('click', () => { window.location.href = 'home.php'; });
        document.getElementById('add-btn').addEventListener('click', () => { window.location.href = 'agregar.php'; });
        document.getElementById('messages-btn').addEventListener('click', () => { window.location.href = 'mensajes.php'; });
        document.getElementById('notifications-btn').addEventListener('click', () => { window.location.href = 'notificaciones.php'; });

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