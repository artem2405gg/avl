<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; img-src * data: blob:; connect-src *; font-src * data:;">
    
    <title>AVL Logistic — платформа грузоперевозок</title>
    
    <meta name="description" content="AVL Logistic — платформа для грузоперевозок без посредников.">
    <meta name="keywords" content="грузоперевозки, поиск перевозчика, заказ грузоперевозки">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://avllogist.ru/">
    <meta property="og:title" content="AVL Logistic — платформа грузоперевозок">
    <meta property="og:description" content="Найдите перевозчика или выгодный заказ.">
    <meta property="og:url" content="https://avllogist.ru/">
    <meta property="og:type" content="website">
    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/icons/generate.php?size=180">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=109902906', 'ym');

    ym(109902906, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/109902906" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #0f172a;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --primary: #000000;
            --primary-hover: #1a1a1a;
            --accent: #f59e0b;
            --accent-glow: rgba(245, 158, 11, 0.2);
            --success: #10b981;
            --danger: #ef4444;
            --info: #3b82f6;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow: 0 1px 6px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.08);
            --font-mono: 'SF Mono', 'Cascadia Code', monospace;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-image: linear-gradient(45deg, #e2e8f0 1px, transparent 1px), linear-gradient(-45deg, #e2e8f0 1px, transparent 1px);
            background-size: 40px 40px; background-position: 0 0, 20px 20px;
            opacity: 0.4; z-index: 0; pointer-events: none;
            animation: gridShift 20s linear infinite;
        }
        @keyframes gridShift {
            0% { background-position: 0 0, 20px 20px; }
            100% { background-position: 40px 40px, 60px 60px; }
        }

        body::after {
            content: ''; position: fixed; top: 0; left: 0; right: 0; height: 4px;
            background: var(--accent); z-index: 9999;
            animation: pulse 3s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 10px var(--accent-glow); }
            50% { box-shadow: 0 0 25px rgba(245, 158, 11, 0.4); }
        }

        .navbar {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 0 24px; height: 72px; display: flex;
            justify-content: space-between; align-items: center;
            position: sticky; top: 4px; z-index: 100;
            transition: box-shadow 0.3s;
        }
        .navbar.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }

        .logo {
            font-size: 20px; font-weight: 900; color: var(--text);
            text-decoration: none; display: flex; align-items: center; gap: 14px;
            letter-spacing: -1px; transition: transform 0.3s;
        }
        .logo:hover { transform: scale(1.02); }
        .logo-mark {
            width: 44px; height: 44px; background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            position: relative; animation: logoGlitch 4s ease-in-out infinite;
        }
        @keyframes logoGlitch {
            0%, 90%, 100% { transform: translate(0); }
            91% { transform: translate(-2px, 1px); }
            92% { transform: translate(2px, -1px); }
            93% { transform: translate(-1px, -1px); }
            94% { transform: translate(1px, 1px); }
            95% { transform: translate(0); }
        }
        .logo-mark::after {
            content: ''; position: absolute; bottom: 0; right: 0;
            width: 12px; height: 12px; background: var(--accent);
            animation: cornerPulse 2s ease-in-out infinite;
        }
        @keyframes cornerPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .logo-mark span { color: white; font-size: 18px; font-weight: 900; z-index: 1; }
        .logo-text { line-height: 1; }
        .logo-text .main { font-size: 16px; letter-spacing: 2px; }
        .logo-text .sub { font-size: 10px; color: var(--text-secondary); letter-spacing: 3px; text-transform: uppercase; }

        .nav-links { display: flex; align-items: center; gap: 2px; }
        .nav-links a {
            color: var(--text-secondary); text-decoration: none;
            font-size: 13px; font-weight: 600; padding: 10px 12px;
            transition: all 0.2s; position: relative; letter-spacing: 0.3px; white-space: nowrap;
        }
        .nav-links a::after {
            content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
            width: 0; height: 2px; background: var(--accent);
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .nav-links a:hover { color: var(--text); }
        .nav-links a:hover::after { width: 20px; }
        .nav-links a.nav-active { color: var(--text); }
        .nav-links a.nav-active::after { width: 20px; background: var(--accent); }

        .user-badge {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 18px 6px 6px; background: var(--border-light);
            font-size: 13px; font-weight: 600; transition: all 0.3s;
            text-decoration: none; color: inherit;
        }
        .user-badge:hover { background: #e2e8f0; transform: translateY(-1px); }
        .user-avatar {
            width: 34px; height: 34px; background: var(--primary); color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px; transition: transform 0.3s;
        }
        .user-badge:hover .user-avatar { transform: scale(1.1); }

        .unread-badge {
            position: absolute; top: 2px; right: 2px;
            background: var(--danger); color: white; font-size: 10px; font-weight: 700;
            padding: 2px 6px; border-radius: 10px; min-width: 18px; text-align: center;
        }

        .burger {
            display: none; background: var(--border-light); border: none;
            font-size: 24px; cursor: pointer; padding: 10px 14px; color: var(--text);
            transition: all 0.3s;
        }
        .burger:hover { background: #e2e8f0; }

        .container { max-width: 1120px; margin: 36px auto; padding: 0 24px; position: relative; z-index: 1; animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .card {
            background: var(--surface); border: 1px solid var(--border);
            padding: 28px; margin-bottom: 16px; position: relative;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            animation: cardIn 0.5s ease-out backwards;
        }
        .card:nth-child(1) { animation-delay: 0s; }
        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.2s; }
        .card:nth-child(4) { animation-delay: 0.3s; }
        @keyframes cardIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card::before {
            content: ''; position: absolute; top: 0; left: 0;
            width: 3px; height: 0; background: var(--accent);
            transition: height 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card:hover::before { height: 40px; }
        .card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 24px; border: 2px solid transparent; text-decoration: none;
            cursor: pointer; font-size: 13px; font-weight: 700; font-family: 'Inter', sans-serif;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            letter-spacing: 0.5px; text-transform: uppercase; position: relative; overflow: hidden;
        }
        .btn::after { content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0; border-radius: 50%; background: rgba(255,255,255,0.3); transition: width 0.6s, height 0.6s, margin 0.6s; }
        .btn:active::after { width: 300px; height: 300px; margin: -150px; }
        .btn-primary { background: var(--primary); color: white; border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-hover); box-shadow: 0 8px 25px rgba(0,0,0,0.2); transform: translateY(-2px); }
        .btn-accent { background: var(--accent); color: var(--primary); border-color: var(--accent); font-weight: 800; animation: accentGlow 2s ease-in-out infinite; }
        @keyframes accentGlow { 0%, 100% { box-shadow: 0 0 0 0 var(--accent-glow); } 50% { box-shadow: 0 0 20px 5px var(--accent-glow); } }
        .btn-accent:hover { box-shadow: 0 8px 25px var(--accent-glow); transform: translateY(-2px); }
        .btn-outline { background: transparent; color: var(--text); border-color: var(--border); }
        .btn-outline:hover { border-color: var(--primary); background: var(--border-light); transform: translateY(-1px); }
        .btn-ghost { background: transparent; color: var(--text-secondary); border: none; }
        .btn-ghost:hover { background: var(--border-light); color: var(--text); }
        .btn-success { background: var(--success); color: white; border-color: var(--success); }
        .btn-danger { background: transparent; color: var(--danger); border-color: var(--danger); }
        .btn-danger:hover { background: var(--danger); color: white; }
        .btn-sm { padding: 8px 16px; font-size: 11px; }
        .btn-lg { padding: 16px 32px; font-size: 15px; }
        .btn-block { width: 100%; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 700; font-size: 10px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 14px; border: 2px solid var(--border);
            font-size: 15px; font-family: 'Inter', sans-serif; transition: all 0.3s; background: var(--border-light);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(0,0,0,0.04); }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .alert { padding: 14px 18px; margin-bottom: 16px; font-size: 14px; font-weight: 600; border-left: 3px solid; animation: slideIn 0.4s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
        .alert-success { background: #f0fdf4; color: #166534; border-color: var(--success); }
        .alert-error { background: #fef2f2; color: #991b1b; border-color: var(--danger); }

        h1 { font-size: 28px; font-weight: 900; letter-spacing: -1px; margin-bottom: 8px; }
        h2 { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 16px; }
        h3 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .text-mono { font-family: var(--font-mono); font-size: 13px; color: var(--text-secondary); }
        .text-muted { color: var(--text-secondary); font-size: 14px; }

        .badge { display: inline-block; padding: 6px 14px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border: 1px solid var(--border); }
        .badge-warning { background: #fffbeb; color: #92400e; border-color: var(--accent); }
        .badge-success { background: #f0fdf4; color: #166534; border-color: var(--success); }

        .info-table { width: 100%; }
        .info-table td { padding: 10px 0; border-bottom: 1px solid var(--border-light); }
        .info-table td:first-child { color: var(--text-secondary); font-weight: 700; width: 30%; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; }

        .bottom-nav {
            display: none; position: fixed !important; bottom: 0 !important; left: 0; right: 0;
            background: var(--surface); border-top: 1px solid var(--border);
            z-index: 9999; padding: 6px 0; padding-bottom: max(6px, env(safe-area-inset-bottom));
        }
        .bottom-nav-inner { display: flex; justify-content: space-around; }
        .bottom-nav a {
            color: var(--text-secondary); text-decoration: none; text-align: center;
            font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 6px 10px; position: relative;
        }
        .bottom-nav a .icon { font-size: 20px; }
        .bottom-nav a.active { color: var(--accent); }

        .mobile-menu {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: var(--surface); z-index: 200; padding: 20px 16px; flex-direction: column; gap: 2px;
            overflow-y: auto;
        }
        .mobile-menu.active { display: flex; }
        .mobile-menu-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .mobile-menu a {
            color: var(--text); text-decoration: none; font-size: 15px; font-weight: 700;
            padding: 14px 16px; border-bottom: 1px solid var(--border-light);
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .close-menu { background: none; border: none; font-size: 28px; cursor: pointer; color: var(--text); }

                .support-float {
            position: fixed;
            bottom: 20px;
            right: 16px;
            width: 46px;
            height: 46px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            cursor: pointer;
            z-index: 9998;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            transition: all 0.3s;
            text-decoration: none;
            opacity: 0.85;
        }
        .support-float:hover {
            opacity: 1;
            transform: scale(1.05);
        }
        .support-float:active { transform: scale(0.95); }
        
        @media (max-width: 768px) {
            .support-float {
                bottom: 90px;
                right: 10px;
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }
        .support-float:hover { transform: scale(1.1); box-shadow: 0 6px 24px rgba(0,0,0,0.3); }
        .support-float:active { transform: scale(0.95); }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .burger { display: block; }
            .bottom-nav { display: block !important; }
            .container { margin: 16px auto; padding: 0 12px 90px 12px !important; }
            .grid-2 { grid-template-columns: 1fr; gap: 12px; }
            h1 { font-size: 22px; }
            h2 { font-size: 18px; }
            .card { padding: 18px; }
            .btn { padding: 10px 18px; font-size: 12px; }
            .support-float { bottom: 80px; right: 14px; width: 50px; height: 50px; font-size: 22px; }
        }
    </style>
</head>
<body>
    <nav class="navbar" id="navbar">
        <a href="/" class="logo">
            <div class="logo-mark"><span>A</span></div>
            <div class="logo-text">
                <div class="main">AVL LOGISTIC</div>
                <div class="sub">Грузоперевозки</div>
            </div>
        </a>

        <div style="display:flex; align-items:center; gap:8px;">
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): 
                    global $pdo;
                    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM messages WHERE receiver_id = ? AND is_read = 0");
                    $stmt->execute([$_SESSION['user_id']]); $unreadMessages = $stmt->fetch()['cnt'];
                    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0");
                    $stmt->execute([$_SESSION['user_id']]); $unreadNotif = $stmt->fetch()['cnt'];
                    $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]); $isAdmin = $stmt->fetch()['is_admin'];
                ?>
                    <a href="/dashboard" class="<?= ($_SERVER['REQUEST_URI'] == '/' || strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0) ? 'nav-active' : '' ?>">📊 Панель</a>
                    <a href="/orders" class="<?= (strpos($_SERVER['REQUEST_URI'], '/orders') === 0 && strpos($_SERVER['REQUEST_URI'], '/orders/my') === false && strpos($_SERVER['REQUEST_URI'], '/orders/create') === false) ? 'nav-active' : '' ?>">📋 Заказы</a>
                    <?php if ($_SESSION['user_role'] === 'owner'): ?>
                        <a href="/orders/create" class="<?= strpos($_SERVER['REQUEST_URI'], '/orders/create') === 0 ? 'nav-active' : '' ?>">➕ Новый</a>
                    <?php endif; ?>
                    <a href="/orders/my">📦 Сделки</a>
                    <a href="/messages" style="position:relative;" class="<?= strpos($_SERVER['REQUEST_URI'], '/messages') === 0 ? 'nav-active' : '' ?>">
                        💬 Чаты
                        <?php if ($unreadMessages > 0): ?><span class="unread-badge msg-counter"><?= $unreadMessages ?></span><?php endif; ?>
                    </a>
                    <?php if ($_SESSION['user_role'] === 'carrier'): ?>
                        <a href="/pricing" class="<?= strpos($_SERVER['REQUEST_URI'], '/pricing') === 0 ? 'nav-active' : '' ?>">💎 Тарифы</a>
                    <?php endif; ?>
                    <a href="/invoices">🧾 Счета</a>
                    <a href="/notifications" style="position:relative;" class="<?= strpos($_SERVER['REQUEST_URI'], '/notifications') === 0 ? 'nav-active' : '' ?>">
                        🔔
                        <?php if ($unreadNotif > 0): ?><span class="unread-badge notif-counter"><?= $unreadNotif ?></span><?php endif; ?>
                    </a>
                    <a href="/profile" class="user-badge">
                        <div class="user-avatar"><?= mb_substr($_SESSION['user_name'] ?? 'U', 0, 1) ?></div>
                        <?= htmlspecialchars(mb_substr($_SESSION['user_name'] ?? 'Пользователь', 0, 12)) ?>
                    </a>
                    <?php if ($isAdmin): ?>
                        <a href="/admin" style="color:var(--accent); font-weight:700;">🛡️ Админ</a>
                    <?php endif; ?>
                    <a href="/logout" style="color:var(--danger);">Выйти</a>
                <?php else: ?>
                    <a href="/login">Вход</a>
                    <a href="/register" class="btn btn-primary btn-sm">Регистрация</a>
                <?php endif; ?>
            </div>
            <button class="burger" onclick="document.getElementById('mobileMenu').classList.add('active')">☰</button>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <span style="font-weight:900; font-size:18px; letter-spacing:2px;">AVL LOGISTIC</span>
            <button class="close-menu" onclick="document.getElementById('mobileMenu').classList.remove('active')">✕</button>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard" onclick="document.getElementById('mobileMenu').classList.remove('active')">📊 Панель</a>
            <a href="/orders" onclick="document.getElementById('mobileMenu').classList.remove('active')">📋 Заказы</a>
            <?php if ($_SESSION['user_role'] === 'owner'): ?>
                <a href="/orders/create" onclick="document.getElementById('mobileMenu').classList.remove('active')">➕ Новый заказ</a>
            <?php endif; ?>
            <a href="/orders/my" onclick="document.getElementById('mobileMenu').classList.remove('active')">📦 Сделки</a>
            <a href="/messages" onclick="document.getElementById('mobileMenu').classList.remove('active')">💬 Чаты</a>
            <?php if ($_SESSION['user_role'] === 'carrier'): ?>
                <a href="/pricing" onclick="document.getElementById('mobileMenu').classList.remove('active')">💎 Тарифы</a>
            <?php endif; ?>
            <a href="/invoices" onclick="document.getElementById('mobileMenu').classList.remove('active')">🧾 Счета</a>
            <a href="/notifications" onclick="document.getElementById('mobileMenu').classList.remove('active')">🔔 Уведомления</a>
            <a href="/profile" onclick="document.getElementById('mobileMenu').classList.remove('active')">👤 Профиль</a>
            <?php if (isset($isAdmin) && $isAdmin): ?>
                <a href="/admin" onclick="document.getElementById('mobileMenu').classList.remove('active')" style="color:var(--accent);">🛡️ Админ-панель</a>
            <?php endif; ?>
            <a href="/logout" style="color:var(--danger);" onclick="document.getElementById('mobileMenu').classList.remove('active')">🚪 Выйти</a>
        <?php else: ?>
            <a href="/login" onclick="document.getElementById('mobileMenu').classList.remove('active')">🔑 Вход</a>
            <a href="/register" onclick="document.getElementById('mobileMenu').classList.remove('active')">📝 Регистрация</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="/dashboard" class="<?= ($_SERVER['REQUEST_URI'] == '/' || strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0) ? 'active' : '' ?>"><span class="icon">📊</span>Панель</a>
            <a href="/orders" class="<?= (strpos($_SERVER['REQUEST_URI'], '/orders') === 0 && strpos($_SERVER['REQUEST_URI'], '/orders/my') === false) ? 'active' : '' ?>"><span class="icon">📋</span>Заказы</a>
            <a href="/messages" style="position:relative;" class="<?= strpos($_SERVER['REQUEST_URI'], '/messages') === 0 ? 'active' : '' ?>"><span class="icon">💬</span>Чаты<?php if (isset($unreadMessages) && $unreadMessages > 0): ?><span style="position:absolute;top:0;right:2px;background:#ef4444;color:white;font-size:9px;padding:1px 5px;border-radius:8px;" class="msg-counter"><?= $unreadMessages ?></span><?php endif; ?></a>
            <a href="/notifications" style="position:relative;" class="<?= strpos($_SERVER['REQUEST_URI'], '/notifications') === 0 ? 'active' : '' ?>"><span class="icon">🔔</span>Увед<?php if (isset($unreadNotif) && $unreadNotif > 0): ?><span style="position:absolute;top:0;right:2px;background:#ef4444;color:white;font-size:9px;padding:1px 5px;border-radius:8px;" class="notif-counter"><?= $unreadNotif ?></span><?php endif; ?></a>
            <a href="/orders/my" class="<?= strpos($_SERVER['REQUEST_URI'], '/orders/my') === 0 ? 'active' : '' ?>"><span class="icon">📦</span>Сделки</a>
        </div>
    </div>
    <?php endif; ?>

    <div class="container">
    <a href="/support" class="support-float" title="Техподдержка">🎧</a>

    <script>
    window.addEventListener('scroll', function() { var n = document.getElementById('navbar'); if (window.scrollY > 10) n.classList.add('scrolled'); else n.classList.remove('scrolled'); });
    if ('serviceWorker' in navigator) { window.addEventListener('load', function() { navigator.serviceWorker.register('/sw.js').catch(function(){}); }); }
    function updateCounters() {
        fetch('/api/counters').then(function(r) { return r.json(); }).then(function(data) {
            document.querySelectorAll('.msg-counter').forEach(function(b) { if (data.messages > 0) { b.textContent = data.messages; b.style.display = 'inline'; } else { b.style.display = 'none'; } });
            document.querySelectorAll('.notif-counter').forEach(function(b) { if (data.notifications > 0) { b.textContent = data.notifications; b.style.display = 'inline'; } else { b.style.display = 'none'; } });
        });
    }
    setInterval(updateCounters, 15000);
    </script>