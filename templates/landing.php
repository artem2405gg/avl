<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVL Logistic — платформа грузоперевозок без посредников</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: #0a0a0a;
            --white: #ffffff;
            --gray: #6b7280;
            --light: #f5f5f5;
            --yellow: #f59e0b;
            --yellow-light: #fef3c7;
            --border: #e5e7eb;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            color: var(--black);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .header {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 0 40px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-decoration: none;
            color: var(--black);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px; height: 40px;
            background: var(--black);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            bottom: 0; right: 0;
            width: 10px; height: 10px;
            background: var(--yellow);
        }

        .logo-icon span { color: white; font-weight: 900; font-size: 18px; z-index: 1; }
        .logo-text { line-height: 1; }
        .logo-main { font-size: 15px; letter-spacing: 2px; }
        .logo-sub { font-size: 9px; color: var(--gray); letter-spacing: 3px; text-transform: uppercase; }

        .header-btns { display: flex; gap: 10px; align-items: center; flex-shrink: 0; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .btn-ghost { background: transparent; color: var(--gray); }
        .btn-ghost:hover { color: var(--black); }
        .btn-black { background: var(--black); color: white; }
        .btn-black:hover { background: #1a1a1a; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
        .btn-yellow { background: var(--yellow); color: var(--black); font-weight: 800; }
        .btn-yellow:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,158,11,0.3); }
        .btn-lg { padding: 18px 36px; font-size: 16px; }

        .hero {
            padding: 160px 40px 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
        }

        .hero-grid {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 40%, transparent 70%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--yellow-light);
            color: #92400e;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 24px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .hero-dot {
            width: 8px; height: 8px;
            background: var(--yellow);
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hero-content { position: relative; z-index: 1; max-width: 750px; margin: 0 auto; }

        .hero h1 {
            font-size: 64px;
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 20px;
        }

        .hero h1 .highlight {
            position: relative;
            display: inline-block;
        }

        .hero h1 .highlight::after {
            content: '';
            position: absolute;
            bottom: 4px; left: 0; right: 0;
            height: 12px;
            background: var(--yellow-light);
            z-index: -1;
        }

        .hero p {
            font-size: 18px;
            color: var(--gray);
            max-width: 500px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .hero-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

        .marquee {
            background: var(--black);
            color: white;
            padding: 16px 0;
            overflow: hidden;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .marquee-inner {
            display: inline-block;
            animation: marquee 25s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .marquee span { margin: 0 30px; }
        .marquee .dot { color: var(--yellow); }

        .stats-section { padding: 80px 40px; background: white; }
        .stats-grid {
            max-width: 900px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center;
        }
        .stat-item .stat-number {
            font-size: 48px; font-weight: 900; letter-spacing: -2px;
            font-family: 'SF Mono', 'Cascadia Code', monospace;
        }
        .stat-item .stat-label {
            font-size: 12px; color: var(--gray); font-weight: 700;
            text-transform: uppercase; letter-spacing: 2px; margin-top: 8px;
        }

        .section { padding: 100px 40px; }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header h2 { font-size: 40px; font-weight: 900; letter-spacing: -1px; margin-bottom: 12px; }
        .section-header p { font-size: 16px; color: var(--gray); }
        .container { max-width: 1000px; margin: 0 auto; }

        /* Заказы */
        .orders-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .order-card {
            background: white; border: 1px solid var(--border);
            border-radius: 12px; padding: 22px; transition: all 0.3s;
            text-align: left;
        }
        .order-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .order-card .order-title { font-weight: 700; font-size: 15px; margin-bottom: 8px; }
        .order-card .order-info { font-size: 13px; color: var(--gray); margin-bottom: 4px; }
        .order-card .order-route { font-size: 12px; color: var(--gray); margin-bottom: 14px; }
        .order-card .order-footer { display: flex; justify-content: space-between; align-items: center; }
        .order-card .order-price { font-size: 22px; font-weight: 800; color: var(--yellow); }
        .order-card .order-time { font-size: 11px; color: #9ca3af; }

        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .step-card {
            background: white; border: 1px solid var(--border);
            padding: 40px 30px; text-align: center; position: relative;
            transition: all 0.3s;
        }
        .step-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .step-number {
            width: 56px; height: 56px; background: var(--black); color: white;
            font-size: 24px; font-weight: 900; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 20px; position: relative;
        }
        .step-number::after {
            content: ''; position: absolute; bottom: 0; right: 0;
            width: 12px; height: 12px; background: var(--yellow);
        }
        .step-card h3 { font-size: 18px; font-weight: 800; margin-bottom: 10px; }
        .step-card p { font-size: 14px; color: var(--gray); line-height: 1.6; }

        .benefits { background: #fafafa; padding: 100px 40px; }
        .benefits-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; max-width: 1000px; margin: 0 auto; }
        .benefit-card {
            background: white; padding: 30px; border-left: 3px solid var(--yellow);
            transition: all 0.3s;
        }
        .benefit-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.06); transform: translateX(4px); }
        .benefit-icon { font-size: 32px; margin-bottom: 12px; }
        .benefit-card h4 { font-size: 16px; font-weight: 800; margin-bottom: 6px; }
        .benefit-card p { font-size: 13px; color: var(--gray); }

        .cta {
            background: var(--black); color: white;
            text-align: center; padding: 100px 40px;
            position: relative; overflow: hidden;
        }
        .cta::before {
            content: ''; position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, transparent 70%);
            top: -200px; right: -200px; border-radius: 50%;
        }
        .cta-content { position: relative; z-index: 1; }
        .cta h2 { font-size: 44px; font-weight: 900; letter-spacing: -1px; margin-bottom: 16px; }
        .cta p { font-size: 16px; color: #9ca3af; margin-bottom: 30px; }
        .btn-white { background: white; color: var(--black); font-size: 16px; padding: 18px 36px; font-weight: 800; }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255,255,255,0.2); }

        .footer {
            background: #111; color: #6b7280;
            padding: 40px; text-align: center; font-size: 13px;
        }
        .footer a { color: var(--yellow); text-decoration: none; }

        @media (max-width: 768px) {
            .header { padding: 0 16px; }
            .header-btns .btn { padding: 10px 14px; font-size: 12px; }
            .header-btns .btn-ghost { padding: 10px 8px; }
            .header-btns { gap: 4px; }
            .hero { padding: 130px 20px 60px; }
            .hero h1 { font-size: 36px; letter-spacing: -1px; }
            .hero p { font-size: 15px; }
            .orders-grid { grid-template-columns: 1fr 1fr; }
            .steps { grid-template-columns: 1fr; }
            .benefits-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
            .stat-item .stat-number { font-size: 32px; }
            .cta h2 { font-size: 28px; }
            .section, .benefits, .cta { padding: 60px 20px; }
            .section-header h2 { font-size: 28px; }
        }

        @media (max-width: 480px) {
            .header-btns .btn { padding: 8px 10px; font-size: 11px; }
            .header-btns .btn-ghost { padding: 8px 5px; }
            .orders-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="/" class="logo">
            <div class="logo-icon"><span>A</span></div>
            <div class="logo-text">
                <div class="logo-main">AVL LOGISTIC</div>
                <div class="logo-sub">Грузоперевозки</div>
            </div>
        </a>
        <div class="header-btns">
            <a href="/login" class="btn btn-ghost">Вход</a>
            <a href="/register" class="btn btn-black">Регистрация</a>
        </div>
    </header>

    <div class="orders-banner" style="background:var(--yellow-light); border-bottom:1px solid var(--yellow); padding:8px; text-align:center; font-weight:700; font-size:13px; color:#92400e; position:fixed; top:70px; left:0; right:0; z-index:999;">
        🚛 На платформе есть активные заказы. <a href="/register" style="color:#92400e;">Зарегистрируйтесь</a> и получите первый заказ!
    </div>

    <section class="hero" style="padding-top:200px;">
        <div class="hero-grid"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <div class="hero-dot"></div>
                Уже работает в России
            </div>
            <h1>
                Грузоперевозки<br>
                <span class="highlight">без посредников</span><br>
                и лишних звонков
            </h1>
            <p>
                Находите проверенных перевозчиков или выгодные заказы в одном окне. 
                От заявки до закрывающих документов — автоматически.
            </p>
            <div class="hero-buttons">
                <a href="/register" class="btn btn-yellow btn-lg">🚀 Попробовать бесплатно</a>
                <a href="#how" class="btn btn-ghost btn-lg">Как работает →</a>
            </div>
        </div>
    </section>

    <div class="marquee">
        <div class="marquee-inner">
            <span>🚛 Грузоперевозки по всей России</span><span class="dot">●</span>
            <span>📄 Автоматические документы</span><span class="dot">●</span>
            <span>💬 Встроенный чат</span><span class="dot">●</span>
            <span>📱 Мобильная версия</span><span class="dot">●</span>
            <span>⭐ Рейтинг перевозчиков</span><span class="dot">●</span>
            <span>🛡️ Безопасные сделки</span><span class="dot">●</span>
            <span>🚛 Грузоперевозки по всей России</span><span class="dot">●</span>
            <span>📄 Автоматические документы</span><span class="dot">●</span>
            <span>💬 Встроенный чат</span><span class="dot">●</span>
            <span>📱 Мобильная версия</span><span class="dot">●</span>
            <span>⭐ Рейтинг перевозчиков</span><span class="dot">●</span>
            <span>🛡️ Безопасные сделки</span><span class="dot">●</span>
        </div>
    </div>

    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-item"><div class="stat-number" style="color:var(--yellow);">0%</div><div class="stat-label">Комиссия для грузовладельцев</div></div>
            <div class="stat-item"><div class="stat-number">24/7</div><div class="stat-label">Доступ к заказам</div></div>
            <div class="stat-item"><div class="stat-number">PDF</div><div class="stat-label">Авто-документы</div></div>
            <div class="stat-item"><div class="stat-number">30</div><div class="stat-label">Дней пробного периода</div></div>
        </div>
    </section>

    <!-- Заказы -->
    <section class="section" style="background:white;">
        <div class="container">
            <div class="section-header">
                <h2>📦 Активные заказы</h2>
                <p>Реальные заказы от грузовладельцев прямо сейчас</p>
            </div>
            <div id="latestOrders" class="orders-grid">
                <div style="text-align:center;padding:30px;grid-column:1/-1;color:var(--gray);">Загрузка...</div>
            </div>
            <div style="text-align:center;margin-top:24px;">
                <a href="/register" class="btn btn-yellow btn-lg">📋 Смотреть все заказы</a>
            </div>
        </div>
    </section>

    <section class="section" id="how">
        <div class="section-header"><h2>Как это работает</h2><p>Три шага — и груз в пути</p></div>
        <div class="container steps">
            <div class="step-card"><div class="step-number">1</div><h3>📋 Создайте заказ</h3><p>Укажите маршрут, тип груза, вес и бюджет. Это займёт меньше минуты.</p></div>
            <div class="step-card"><div class="step-number">2</div><h3>🤝 Выберите перевозчика</h3><p>Получите отклики от водителей. Сравните цены и рейтинг. Выберите лучшего.</p></div>
            <div class="step-card"><div class="step-number">3</div><h3>📦 Отслеживайте</h3><p>Следите за статусом груза онлайн. Получите готовые документы в PDF.</p></div>
        </div>
    </section>

    <section class="benefits">
        <div class="section-header"><h2>Почему AVL Logistic?</h2><p>Мы учли всё, что нужно для удобной работы</p></div>
        <div class="benefits-grid">
            <div class="benefit-card"><div class="benefit-icon">📄</div><h4>Автоматические документы</h4><p>Договор-заявка и накладная формируются сами. Распечатайте в один клик.</p></div>
            <div class="benefit-card"><div class="benefit-icon">💬</div><h4>Встроенный чат</h4><p>Общайтесь с заказчиком или водителем прямо на платформе. Никаких WhatsApp.</p></div>
            <div class="benefit-card"><div class="benefit-icon">📱</div><h4>Работает с телефона</h4><p>Полноценная мобильная версия. Откликайтесь на заказы и меняйте статусы на ходу.</p></div>
            <div class="benefit-card"><div class="benefit-icon">⭐</div><h4>Рейтинг и отзывы</h4><p>Выбирайте надёжных партнёров по реальным отзывам и истории сделок.</p></div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-content">
            <h2>Готовы начать?</h2>
            <p>Зарегистрируйтесь бесплатно и получите доступ ко всем функциям платформы.</p>
            <a href="/register" class="btn btn-white btn-lg">🚀 Создать аккаунт</a>
        </div>
    </section>

    <footer class="footer">
        <p>© 2026 AVL Logistic. Платформа для грузоперевозок.</p>
        <p style="margin-top:8px;"><a href="/login">Вход</a> · <a href="/register">Регистрация</a> · <a href="mailto:info@avllogist.ru">info@avllogist.ru</a></p>
    </footer>

    <script>
    fetch('/api/latest_orders')
        .then(function(r) { return r.json(); })
        .then(function(orders) {
            var container = document.getElementById('latestOrders');
            if (orders.length === 0) {
                container.innerHTML = '<div style="text-align:center;padding:30px;grid-column:1/-1;color:var(--gray);">Пока нет активных заказов. Станьте первым грузовладельцем!</div>';
                return;
            }
            container.innerHTML = orders.map(function(o) {
                var daysAgo = Math.floor((new Date() - new Date(o.created_at)) / 86400000);
                return '<div class="order-card"><div class="order-title">📦 ' + o.title + '</div><div class="order-info">🚛 ' + o.cargo_type + ' · ' + o.weight + ' т</div><div class="order-route">📍 ' + o.pickup_address.split(',')[0] + ' → ' + o.delivery_address.split(',')[0] + '</div><div class="order-footer"><span class="order-price">' + parseInt(o.price).toLocaleString('ru-RU') + ' ₽</span><span class="order-time">' + (daysAgo === 0 ? 'Сегодня' : daysAgo + ' дн.') + '</span></div></div>';
            }).join('');
        });
    </script>

</body>
</html>