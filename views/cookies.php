<?php
$cookie_consent = isset($_COOKIE['cookies_accepted']) ? $_COOKIE['cookies_accepted'] : null;
?>

<style>
    .cookie-banner {
        position: fixed;
        bottom: 70px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 420px;
        background: rgba(17, 17, 17, 0.95);
        border: 2px solid #ff007f;
        box-shadow: 0 0 15px rgba(255, 0, 127, 0.6);
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 99999;
        backdrop-filter: blur(8px);
        font-family: 'Comic Sans MS', 'Courier New', sans-serif;
        box-sizing: border-box;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .cookie-banner.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translate(-50%, 20px);
        display: none !important;
    }

    .cookie-title {
        color: #ffb6c1;
        font-size: 13px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cookie-text {
        color: #ffffff;
        font-size: 11px;
        line-height: 1.3;
    }

    .cookie-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .cookie-btn {
        background: linear-gradient(90deg, #ff007f, #ff66b2);
        border: 1px solid #ffffff;
        color: #ffffff;
        font-size: 11px;
        font-weight: bold;
        padding: 6px 14px;
        border-radius: 15px;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .cookie-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 0 8px rgba(255, 0, 127, 0.8);
    }

    .cookie-btn-decline {
        background: transparent;
        border: 1px solid #ffb6c1;
        color: #ffb6c1;
    }
</style>

<?php if (!$cookie_consent): ?>
<div class="cookie-banner" id="cookieBanner">
    <div class="cookie-title">🍪 ¡Atención Cyber Angel!</div>
    <div class="cookie-text">
        Usamos cookies para mejorar tu experiencia, recordar tus temas de color, imágenes de chat y preferencias en toda la app. <3
    </div>
    <div class="cookie-buttons">
        <button class="cookie-btn cookie-btn-decline" onclick="declineGlobalCookies()">Rechazar</button>
        <button class="cookie-btn" onclick="acceptGlobalCookies()">Aceptar todas ✨</button>
    </div>
</div>
<?php endif; ?>

<script>
    function acceptGlobalCookies() {
        document.cookie = "cookies_accepted=true; path=/; max-age=" + (86400 * 365);
        const banner = document.getElementById('cookieBanner');
        if (banner) banner.classList.add('hidden');
    }

    function declineGlobalCookies() {
        document.cookie = "cookies_accepted=false; path=/; max-age=" + (86400 * 7);
        const banner = document.getElementById('cookieBanner');
        if (banner) banner.classList.add('hidden');
    }
</script>