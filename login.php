<?php
session_start(); // Arranca la sesión de PHP
require_once 'conexion.php'; // Conexión a la base de datos con PDO

$mensaje = '';
$accion = $_GET['action'] ?? 'login'; // Controla si muestra login o registro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_type = $_POST['form_type'] ?? 'login';

    if ($form_type === 'login') {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($usuario) && !empty($password)) {
            try {
                // Consulta preparada (¡clave para evitar Inyección SQL!)
                $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['usuario' => $usuario]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && $user['password'] === $password) {
                    $token_sesion = uniqid('cyber_', true);

                    // Guarda el token en la BD
                    $updateStmt = $pdo->prepare("UPDATE usuarios SET session_token = ? WHERE id = ?");
                    $updateStmt->execute([$token_sesion, $user['id']]);

                    // Inicializa las variables de sesión
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['usuario'];
                    $_SESSION['login_token'] = $token_sesion;

                    header("Location: views/home.php");
                    exit();
                } else {
                    $mensaje = "Usuario o contraseña incorrectos.";
                }
            } catch (PDOException $e) {
                $mensaje = "Error en la base de datos: " . $e->getMessage();
            }
        } else {
            $mensaje = "Por favor, completa todos los campos.";
        }
    } elseif ($form_type === 'register') {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (!empty($usuario) && !empty($password) && !empty($confirm_password)) {
            if ($password === $confirm_password) {
                try {
                    // Valida si el usuario ya existe en la BD
                    $checkStmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario");
                    $checkStmt->execute(['usuario' => $usuario]);
                    
                    if ($checkStmt->rowCount() > 0) {
                        $mensaje = "Ese nombre de usuario ya está en uso :(";
                    } else {
                        // Inserta el nuevo registro con consulta preparada
                        $sql = "INSERT INTO usuarios (usuario, password) VALUES (:usuario, :password)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'usuario' => $usuario,
                            'password' => $password
                        ]);

                        $mensaje = "¡Registro exitoso! Ya podés iniciar sesión ✨";
                        $accion = 'login';
                    }
                } catch (PDOException $e) {
                    $mensaje = "Error en la base de datos: " . $e->getMessage();
                }
            } else {
                $mensaje = "Las contraseñas no coinciden :(";
            }
        } else {
            $mensaje = "Por favor, completa todos los campos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CyberAngel.MP3 - Acceso</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Slackey&display=swap" rel="stylesheet">
    <style>
        *, body, a, button, select, input {
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="%23ffb6c1" stroke="%23ff007f" stroke-width="1.5"><path d="M4.5 3.5L11.5 20.5L14.5 13.5L21.5 10.5L4.5 3.5Z"/></svg>'), auto !important;
        }
        body {
            background-color: #000000;
            margin: 0; padding: 0;
            font-family: 'Slackey', cursive, sans-serif;
            height: 100vh; display: flex; justify-content: center; align-items: center; overflow: hidden;
        }
        .os-window {
            width: 580px; max-width: 95vw; background-color: #000000;
            border: 4px solid #ffb6c1; box-shadow: 0 0 25px rgba(255, 182, 193, 0.4);
            position: relative; margin-bottom: 50px;
        }
        .os-header {
            background-color: #ffb6c1; padding: 12px 16px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .os-title-text { font-size: 20px; color: #d64573; text-transform: uppercase; }
        .os-controls { display: flex; gap: 6px; }
        .os-btn { width: 20px; height: 18px; background-color: #ffb6c1; border: 1.5px solid #000; display: flex; justify-content: center; align-items: center; cursor: pointer; }
        .login-body { padding: 25px 20px 45px 20px; text-align: center; }
        .user-icon-container { width: 60px; height: 60px; border: 3px solid #ffb6c1; border-radius: 50%; margin: 0 auto 15px auto; overflow: hidden; }
        .user-avatar-img { width: 100%; height: 100%; object-fit: cover; }
        .login-form { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .input-box { width: 70%; position: relative; display: flex; align-items: center; }
        .login-input {
            width: 100%; padding: 10px 45px 10px 15px; background-color: #000;
            border: 2px solid #ffb6c1; color: #ffb6c1; text-align: center; font-family: inherit; font-size: 13px; outline: none;
        }
        .eye-toggle-btn {
            position: absolute; right: 8px; background: #000; border: 2px solid #ffb6c1;
            width: 26px; height: 26px; border-radius: 50%; cursor: pointer; overflow: hidden; display: flex; align-items: center; justify-content: center;
        }
        .eye-toggle-btn img { width: 100%; height: 100%; object-fit: cover; }
        .login-submit-btn {
            background-color: #ffb6c1; color: #000; border: 2px solid #ff007f;
            padding: 8px 20px; font-family: inherit; font-size: 12px; cursor: pointer; border-radius: 8px; margin-top: 5px;
        }
        .forgot-link { color: #ffb6c1; font-size: 11px; text-decoration: underline; cursor: pointer; margin-top: 5px; display: inline-block; }
        .custom-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.7);
            display: flex; justify-content: center; align-items: center; z-index: 999; opacity: 0; pointer-events: none; transition: opacity 0.2s;
        }
       ¿ .custom-modal-overlay.active { opacity: 1; pointer-events: auto; }
        .custom-modal { width: 380px; background-color: #000; border: 3px solid #ffb6c1; }
        .custom-modal-header { background-color: #ffb6c1; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center; }
        .custom-modal-body { padding: 25px 20px; text-align: center; color: #ffb6c1; font-size: 13px; }
        .custom-modal-btn { background-color: #ffb6c1; color: #000; border: 2px solid #ff007f; padding: 6px 20px; font-family: inherit; font-size: 12px; cursor: pointer; border-radius: 6px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="os-window">
        <div class="os-header">
            <div class="os-title-text"><?php echo ($accion === 'register') ? 'Register' : 'Login'; ?></div>
            <div class="os-controls">
                <div class="os-btn" onclick="closeWindow()"><span style="font-weight: bold; color: #000;">×</span></div>
            </div>
        </div>
        <div class="login-body">
            <div class="user-icon-container">
                <img src="img/logo.jfif" alt="Avatar" class="user-avatar-img">
            </div>

            <!-- Alterna dinámicamente entre el formulario de Login y Registro -->
            <?php if ($accion === 'login'): ?>
                <form class="login-form" action="login.php" method="POST">
                    <input type="hidden" name="form_type" value="login">
                    <div class="input-box">
                        <input type="text" name="usuario" placeholder="Username" class="login-input" required>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" id="passwordField" placeholder="Password" class="login-input" required>
                        <button type="button" class="eye-toggle-btn" onclick="togglePassword('passwordField')">
                            <img src="img/ojo.jfif" alt="Ver">
                        </button>
                    </div>
                    <button type="submit" class="login-submit-btn">ENTER</button>
                    <a href="login.php?action=register" class="forgot-link">¿No tenés cuenta? Registrate acá ✨</a>

                    <?php if (!empty($mensaje)): ?>
                        <div style="color: #ff3366; font-size: 11px; margin-top: 5px;"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <form class="login-form" action="login.php?action=register" method="POST">
                    <input type="hidden" name="form_type" value="register">
                    <div class="input-box">
                        <input type="text" name="usuario" placeholder="Choose a Username" class="login-input" required>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" id="regPassField" placeholder="Password" class="login-input" required>
                    </div>
                    <div class="input-box">
                        <input type="password" name="confirm_password" id="confirmPassField" placeholder="Confirm Password" class="login-input" required>
                    </div>
                    <button type="submit" class="login-submit-btn">REGISTER</button>
                    <a href="login.php" class="forgot-link">¿Ya tenés cuenta? Iniciar Sesión 💖</a>

                    <?php if (!empty($mensaje)): ?>
                        <div style="color: #ff3366; font-size: 11px; margin-top: 5px;"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Muestra u oculta la contraseña con el botón del ojito
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }
        function closeWindow() {
            alert('Acción no permitida en esta DEMO');
        }
    </script>
</body>
</html>