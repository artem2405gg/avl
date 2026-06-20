<?php require_once BASE_PATH . '/templates/header.php'; 

global $pdo;

// Проверяем текущую подписку
$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND status = 'active' ORDER BY expires_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$currentSub = $stmt->fetch();

// Считаем отклики в этом месяце
$stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids WHERE carrier_id = ? AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())");
$stmt->execute([$_SESSION['user_id']]);
$bidsThisMonth = $stmt->fetch()['cnt'];

// Проверяем, когда истекает пробный период
$stmt = $pdo->prepare("SELECT expires_at FROM subscriptions WHERE user_id = ? AND plan = 'free' AND status = 'active' ORDER BY expires_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$trial = $stmt->fetch();
$daysLeft = 0;
if ($trial) {
    $expires = new DateTime($trial['expires_at']);
    $now = new DateTime();
    $diff = $now->diff($expires);
    $daysLeft = $diff->days;
}

// Проверяем неоплаченные счета
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE user_id = ? AND status = 'pending' ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$pendingInvoice = $stmt->fetch();
?>

<style>
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 32px;
    }
    
    .pricing-card {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 36px 28px;
        text-align: center;
        transition: all 0.3s;
        position: relative;
    }
    
    .pricing-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }
    
    .pricing-card.featured {
        border: 2px solid var(--accent);
        box-shadow: 0 0 30px var(--accent-glow);
    }
    
    .pricing-badge {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--accent);
        color: var(--black);
        padding: 6px 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .pricing-name {
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 16px;
        color: var(--text-secondary);
    }
    
    .pricing-price {
        font-size: 48px;
        font-weight: 900;
        font-family: var(--font-mono);
        letter-spacing: -2px;
        margin-bottom: 4px;
    }
    
    .pricing-period {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 24px;
    }
    
    .pricing-features {
        list-style: none;
        text-align: left;
        margin-bottom: 28px;
    }
    
    .pricing-features li {
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .pricing-features li .check {
        color: var(--success);
        font-weight: 700;
        font-size: 16px;
    }
    
    .pricing-features li .cross {
        color: var(--danger);
        font-weight: 700;
    }
    
    .current-plan {
        background: var(--border-light);
        padding: 10px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-secondary);
        margin-top: 8px;
    }
    
    .current-plan.pending {
        background: #fffbeb;
        color: #92400e;
    }
    
    .trial-alert {
        background: var(--yellow-light);
        border-left: 3px solid var(--accent);
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    
    .faq-section {
        margin-top: 60px;
    }
    
    .faq-item {
        border-bottom: 1px solid var(--border);
        padding: 16px 0;
    }
    
    .faq-question {
        font-weight: 700;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 15px;
    }
    
    .faq-answer {
        color: var(--text-secondary);
        font-size: 13px;
        margin-top: 8px;
        display: none;
        line-height: 1.6;
    }
    
    .faq-answer.open { display: block; }
    
    @media (max-width: 768px) {
        .pricing-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .pricing-price { font-size: 36px; }
    }
</style>

<h1>💎 Тарифы</h1>
<p class="text-muted">Выберите подходящий план для работы на платформе</p>

<?php if (isset($_GET['activated'])): ?>
    <div class="alert alert-success">
        ✅ <?= $_SESSION['success'] ?? 'Тариф активирован!' ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['invoiced'])): ?>
    <div class="alert alert-success">
        ✅ <?= $_SESSION['success'] ?? 'Счёт создан!' ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
        ❌ <?= $_SESSION['error'] ?? 'Ошибка!' ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if ($pendingInvoice): ?>
    <div class="alert alert-warning" style="background: #fffbeb; color: #92400e; border-color: var(--accent);">
        ⏳ Ожидает оплаты: тариф <strong><?= $pendingInvoice['plan'] ?></strong> — 
        <strong><?= number_format($pendingInvoice['amount'], 0, ',', ' ') ?> ₽</strong><br>
        <small>Счёт создан <?= date('d.m.Y H:i', strtotime($pendingInvoice['created_at'])) ?>.
        Напишите нам на <a href="mailto:info@avllogist.ru">info@avllogist.ru</a> для оплаты.</small>
    </div>
<?php endif; ?>

<?php if ($trial && $daysLeft > 0 && (!$currentSub || $currentSub['plan'] == 'free')): ?>
    <div class="trial-alert">
        <strong>🎁 Пробный период активен</strong><br>
        <small>Осталось <?= $daysLeft ?> дней. Использовано откликов: <?= $bidsThisMonth ?> из 3</small>
    </div>
<?php elseif ($currentSub && $currentSub['plan'] != 'free'): ?>
    <div class="alert alert-success">
        ✅ У вас активна подписка <strong><?= $currentSub['plan'] ?></strong> до <?= date('d.m.Y', strtotime($currentSub['expires_at'])) ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['limit'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'] ?? 'Лимит откликов исчерпан.' ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="pricing-grid">
    
    <!-- Бесплатный -->
    <div class="pricing-card">
        <div class="pricing-name">🚀 Старт</div>
        <div class="pricing-price">0 ₽</div>
        <div class="pricing-period">навсегда</div>
        <ul class="pricing-features">
            <li><span class="check">✓</span> 3 отклика в месяц</li>
            <li><span class="check">✓</span> Просмотр всех заказов</li>
            <li><span class="check">✓</span> Чат с заказчиком</li>
            <li><span class="check">✓</span> Базовые документы (PDF)</li>
            <li><span class="cross">✗</span> Приоритет в выдаче</li>
            <li><span class="cross">✗</span> Расширенная аналитика</li>
            <li><span class="cross">✗</span> ЭДО интеграция</li>
            <li><span class="cross">✗</span> Мульти-аккаунт</li>
        </ul>
        <?php if ($currentSub && $currentSub['plan'] == 'free'): ?>
            <div class="current-plan">Текущий план</div>
        <?php else: ?>
            <form method="POST" action="/subscribe">
                <input type="hidden" name="plan" value="free">
                <button type="submit" class="btn btn-outline btn-block">Выбрать</button>
            </form>
        <?php endif; ?>
        <div style="font-size:11px; color:var(--text-secondary); margin-top:8px;">
            Использовано: <?= $bidsThisMonth ?>/3 в этом месяце
        </div>
    </div>
    
    <!-- Базовый -->
    <div class="pricing-card featured">
        <div class="pricing-badge">Популярный</div>
        <div class="pricing-name">💼 Базовый</div>
        <div class="pricing-price">1 990 ₽</div>
        <div class="pricing-period">в месяц</div>
        <ul class="pricing-features">
            <li><span class="check">✓</span> 50 откликов в месяц</li>
            <li><span class="check">✓</span> Просмотр всех заказов</li>
            <li><span class="check">✓</span> Чат с заказчиком</li>
            <li><span class="check">✓</span> Полный пакет документов</li>
            <li><span class="check">✓</span> Приоритет в выдаче</li>
            <li><span class="check">✓</span> Хранение документов 1 год</li>
            <li><span class="cross">✗</span> ЭДО интеграция</li>
            <li><span class="cross">✗</span> Мульти-аккаунт</li>
        </ul>
        <?php if ($currentSub && $currentSub['plan'] == 'basic'): ?>
            <div class="current-plan">Текущий план</div>
        <?php elseif ($pendingInvoice && $pendingInvoice['plan'] == 'basic'): ?>
            <div class="current-plan pending">⏳ Ожидает оплаты</div>
        <?php else: ?>
            <form method="POST" action="/subscribe">
                <input type="hidden" name="plan" value="basic">
                <button type="submit" class="btn btn-accent btn-block">Выбрать</button>
            </form>
        <?php endif; ?>
    </div>
    
    <!-- Pro -->
    <div class="pricing-card">
        <div class="pricing-name">🏢 Pro</div>
        <div class="pricing-price">4 990 ₽</div>
        <div class="pricing-period">в месяц</div>
        <ul class="pricing-features">
            <li><span class="check">✓</span> Безлимитные отклики</li>
            <li><span class="check">✓</span> Просмотр всех заказов</li>
            <li><span class="check">✓</span> Чат с заказчиком</li>
            <li><span class="check">✓</span> Полный пакет документов</li>
            <li><span class="check">✓</span> Приоритет в выдаче</li>
            <li><span class="check">✓</span> Хранение документов 3 года</li>
            <li><span class="check">✓</span> ЭДО интеграция</li>
            <li><span class="check">✓</span> Мульти-аккаунт (до 5 водителей)</li>
            <li><span class="check">✓</span> Аналитика и отчёты</li>
            <li><span class="check">✓</span> Персональный менеджер</li>
        </ul>
        <?php if ($currentSub && $currentSub['plan'] == 'pro'): ?>
            <div class="current-plan">Текущий план</div>
        <?php elseif ($pendingInvoice && $pendingInvoice['plan'] == 'pro'): ?>
            <div class="current-plan pending">⏳ Ожидает оплаты</div>
        <?php else: ?>
            <form method="POST" action="/subscribe">
                <input type="hidden" name="plan" value="pro">
                <button type="submit" class="btn btn-primary btn-block">Выбрать</button>
            </form>
        <?php endif; ?>
    </div>
    
</div>

<!-- FAQ -->
<div class="faq-section">
    <h3>Частые вопросы</h3>
    
    <div class="faq-item">
        <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('open')">
            Как оплатить подписку?
            <span>▼</span>
        </div>
        <div class="faq-answer">
            При выборе платного тарифа создаётся счёт. Напишите нам на <a href="mailto:info@avllogist.ru">info@avllogist.ru</a>, и мы выставим счёт на оплату. После оплаты подписка активируется в течение часа.
        </div>
    </div>
    
    <div class="faq-item">
        <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('open')">
            Можно ли сменить тариф?
            <span>▼</span>
        </div>
        <div class="faq-answer">
            Да, вы можете перейти на другой тариф в любой момент. Остаток дней по текущему тарифу будет учтён при оплате нового.
        </div>
    </div>
    
    <div class="faq-item">
        <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('open')">
            Что будет, если закончатся отклики?
            <span>▼</span>
        </div>
        <div class="faq-answer">
            Вы всегда можете перейти на платный тариф, чтобы увеличить лимит откликов. Бесплатный тариф даёт 3 отклика в месяц.
        </div>
    </div>
    
    <div class="faq-item">
        <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('open')">
            Нужно ли платить грузовладельцам?
            <span>▼</span>
        </div>
        <div class="faq-answer">
            Нет, для грузовладельцев платформа полностью бесплатна. Мы берём комиссию только с перевозчиков за доступ к заказам.
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>