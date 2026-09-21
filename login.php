<?php
// Conexión a la base de datos (ajustá el archivo de conexión si el tuyo se llama distinto)
require_once 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($usuario) && !empty($password)) {
        // Insertamos los datos en tu tabla de MySQL
        $sql = "INSERT INTO usuarios (usuario, password) VALUES (:usuario, :password)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute(['usuario' => $usuario, 'password' => $password])) {
            // Si se guarda bien, redirige a home.php dentro de views
            header("Location: views/home.php");
            exit();
        } else {
            $mensaje = "Error al guardar en la base de datos.";
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CyberAngel.MP3 - Log In</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Slackey&display=swap" rel="stylesheet">
    <style>
        *, body, a, button, select, input {
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="%23ffb6c1" stroke="%23ff007f" stroke-width="1.5"><path d="M4.5 3.5L11.5 20.5L14.5 13.5L21.5 10.5L4.5 3.5Z"/></svg>'), auto !important;
        }

        body {
            background-color: #000000;
            margin: 0;
            padding: 0;
            font-family: 'Slackey', cursive, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .os-window {
            width: 580px;
            max-width: 95vw;
            background-color: #000000;
            border: 4px solid #ffb6c1;
            box-shadow: 0 0 25px rgba(255, 182, 193, 0.4);
            position: relative;
            margin-bottom: 50px;
            transition: width 0.3s ease;
        }

        .os-header {
            background-color: #ffb6c1;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ffb6c1;
        }

        .os-title-container {
            display: flex;
            align-items: center;
        }

        .os-title-text {
            font-family: 'Slackey', cursive, sans-serif;
            font-size: 20px;
            color: #d64573;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            user-select: none;
            text-shadow: 0 0 2px rgba(255, 255, 255, 0.3);
        }

        .os-controls {
            display: flex;
            gap: 6px;
        }

        .os-btn {
            width: 20px;
            height: 18px;
            background-color: #ffb6c1;
            border: 1.5px solid #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .btn-minimize {
            border-bottom: 2px solid #000000;
            height: 4px;
            margin-top: 8px;
        }

        .btn-maximize {
            width: 10px;
            height: 10px;
            border: 1.5px solid #000000;
        }

        .btn-close {
            font-size: 13px;
            font-weight: bold;
            color: #000000;
            line-height: 1;
        }

        .login-body {
            padding: 25px 20px 45px 20px;
            text-align: center;
        }

        .user-icon-container {
            width: 60px;
            height: 60px;
            border: 3px solid #ffb6c1;
            border-radius: 50%;
            margin: 0 auto 15px auto;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000;
        }

        .user-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-title-container {
            margin-bottom: 25px;
        }

        .login-title-img {
            height: 45px;
            object-fit: contain;
            filter: drop-shadow(0 0 6px rgba(255, 0, 127, 0.5));
        }

        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .input-box {
            width: 70%;
            position: relative;
            display: flex;
            align-items: center;
        }

        .login-input {
            width: 100%;
            padding: 10px 45px 10px 15px;
            background-color: #000;
            border: 2px solid #ffb6c1;
            color: #ffb6c1;
            text-align: center;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            box-sizing: border-box;
        }

        .login-input::placeholder {
            color: #ffb6c1;
            opacity: 0.8;
        }

        .eye-toggle-btn {
            position: absolute;
            right: 8px;
            background: #000;
            border: 2px solid #ffb6c1;
            padding: 0;
            cursor: pointer;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 5px rgba(255, 182, 193, 0.4);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .eye-toggle-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .eye-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 8px rgba(255, 0, 127, 0.8);
        }

        .login-submit-btn {
            background-color: #ffb6c1;
            color: #000;
            border: 2px solid #ff007f;
            padding: 8px 20px;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
            border-radius: 8px;
            margin-top: 5px;
            transition: transform 0.1s ease, background-color 0.2s ease;
        }

        .login-submit-btn:hover {
            background-color: #ff007f;
            color: #fff;
            transform: scale(1.05);
        }

        .separator {
            color: #ffb6c1;
            font-size: 12px;
            margin: 10px 0 5px 0;
        }

        .social-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-top: 10px;
        }

        .social-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(255, 182, 193, 0.5);
            transition: transform 0.2s;
        }

        .social-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .social-box:hover {
            transform: scale(1.08);
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
        }

        .nav-item:hover {
            transform: scale(1.15) translateY(-2px);
            filter: drop-shadow(0 0 8px #ff007f);
        }

        .nav-item:active {
            transform: scale(0.95);
        }

        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .custom-modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .custom-modal {
            width: 380px;
            max-width: 90vw;
            background-color: #000000;
            border: 3px solid #ffb6c1;
            box-shadow: 0 0 20px rgba(255, 182, 193, 0.6);
        }

        .custom-modal-header {
            background-color: #ffb6c1;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-modal-title {
            font-size: 14px;
            color: #d64573;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .custom-modal-body {
            padding: 25px 20px;
            text-align: center;
            color: #ffb6c1;
            font-size: 13px;
        }

        .custom-modal-btn {
            background-color: #ffb6c1;
            color: #000;
            border: 2px solid #ff007f;
            padding: 6px 20px;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
            border-radius: 6px;
            margin-top: 15px;
            transition: transform 0.1s ease, background-color 0.2s ease;
        }

        .custom-modal-btn:hover {
            background-color: #ff007f;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div class="os-window">
        <div class="os-header">
            <div class="os-title-container">
                <span class="os-title-text">DEMO</span>
            </div>
            <div class="os-controls">
                <div class="os-btn" onclick="minimizeWindow()"><div class="btn-minimize"></div></div>
                <div class="os-btn" onclick="maximizeWindow()"><div class="btn-maximize"></div></div>
                <div class="os-btn" onclick="closeWindow()"><span class="btn-close">×</span></div>
            </div>
        </div>

        <div class="login-body">
            <div class="user-icon-container">
                <img src="img/logo.jfif" alt="User Profile" class="user-avatar-img">
            </div>
            
            <div class="login-title-container">
                <img src="img/login-title.jpg" alt="LOG IN" class="login-title-img">
            </div>

            <!-- Formulario conectado a PHP con method POST -->
            <form class="login-form" id="loginForm" action="login.php" method="POST">
                <div class="input-box">
                    <input type="text" name="usuario" id="userInput" placeholder="email ID or Username" class="login-input" required>
                </div>

                <div class="input-box">
                    <input type="password" name="password" id="passwordField" placeholder="password" class="login-input" required>
                    <button type="button" class="eye-toggle-btn" onclick="togglePassword()">
                        <img src="img/ojo.jfif" alt="Mostrar contraseña" id="eyeIcon">
                    </button>
                </div>

                <button type="submit" class="login-submit-btn">ENTER</button>

                <?php if (!empty($mensaje)): ?>
                    <div style="color: #ff3366; font-size: 11px; margin-top: 5px;"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>

                <div class="separator">- or continue with -</div>

                <div class="social-container">
                    <div class="social-box" onclick="socialLogin('WhatsApp')"><img src="img/whatsapp.jfif" alt="WhatsApp"></div>
                    <div class="social-box" onclick="socialLogin('Google')"><img src="img/google.jfif" alt="Google"></div>
                    <div class="social-box" onclick="socialLogin('Instagram')"><img src="img/instagram.jfif" alt="Instagram"></div>
                </div>
            </form>
        </div>
    </div>

    <div class="bottom-bar">
        <button class="nav-item" title="Perfil (Index)" onclick="window.location.href='views/perfil.php'">
            <img src="img/perfil.jfif" alt="Perfil">
        </button>
        <button class="nav-item" title="Match" onclick="window.location.href='views/match.html'">
            <img src="img/match.jfif" alt="Match">
        </button>
        <button class="nav-item" title="Agregar" onclick="window.location.href='views/agregar.html'">
            <img src="img/simbolomas.jfif" alt="Agregar">
        </button>
        <button class="nav-item" title="Mensajes" onclick="window.location.href='views/mensajes.html'">
            <img src="img/mensajes.jfif" alt="Mensajes">
        </button>
        <button class="nav-item" title="Notificaciones" onclick="window.location.href='views/notificaciones.html'">
            <img src="img/notificaciones.jfif" alt="Notificaciones">
        </button>
    </div>

    <div class="custom-modal-overlay" id="customModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <span class="custom-modal-title">CyberAngel.MP3</span>
                <div class="os-btn" onclick="closeCustomAlert()"><span class="btn-close">×</span></div>
            </div>
            <div class="custom-modal-body">
                <p id="modalMessage">Mensaje aquí</p>
                <button class="custom-modal-btn" id="modalActionBtn" onclick="closeCustomAlert()">ACEPTAR</button>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordField');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        function showCustomAlert(message) {
            document.getElementById('modalMessage').innerText = message;
            document.getElementById('customModal').classList.add('active');
        }

        function closeCustomAlert() {
            document.getElementById('customModal').classList.remove('active');
        }

        function socialLogin(provider) {
            showCustomAlert('Conectando con ' + provider + '...');
        }

        function minimizeWindow() {
            const windowEl = document.querySelector('.os-window');
            windowEl.style.transform = 'scale(0)';
            windowEl.style.transition = 'transform 0.3s ease';
            setTimeout(() => {
                showCustomAlert('Ventana minimizada. Recarga la página para restaurarla.');
            }, 300);
        }

        function maximizeWindow() {
            const windowEl = document.querySelector('.os-window');
            if (windowEl.style.width === '90vw') {
                windowEl.style.width = '580px';
            } else {
                windowEl.style.width = '90vw';
            }
        }

        function closeWindow() {
            showCustomAlert('Acción no permitida en esta DEMO');
        }
    </script>

</body>
</html>