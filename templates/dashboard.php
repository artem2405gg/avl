<?php
global $pdo;
require_once BASE_PATH . '/templates/header.php';

if ($_SESSION['user_role'] === 'owner') {
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM orders WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalOrders = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM orders WHERE user_id = ? AND status = 'new'");
    $stmt->execute([$_SESSION['user_id']]);
    $newOrders = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM orders WHERE user_id = ? AND status IN ('accepted','pickup','loaded','in_transit','arrived','unloaded')");
    $stmt->execute([$_SESSION['user_id']]);
    $activeOrders = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM orders WHERE user_id = ? AND status = 'completed'");
    $stmt->execute([$_SESSION['user_id']]);
    $completedOrders = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(price), 0) as total FROM orders WHERE user_id = ? AND status = 'completed'");
    $stmt->execute([$_SESSION['user_id']]);
    $totalSpent = $stmt->fetch()['total'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids b JOIN orders o ON b.order_id = o.id WHERE o.user_id = ? AND b.status = 'pending'");
    $stmt->execute([$_SESSION['user_id']]);
    $pendingBids = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status NOT IN ('completed','cancelled') ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $activeOrdersList = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'carrier'");
    $carriersCount = $stmt->fetch()['cnt'];

} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids WHERE carrier_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalBids = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids WHERE carrier_id = ? AND status = 'accepted'");
    $stmt->execute([$_SESSION['user_id']]);
    $acceptedBids = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids b JOIN orders o ON b.order_id = o.id WHERE b.carrier_id = ? AND b.status = 'accepted' AND o.status IN ('pickup','loaded','in_transit','arrived','unloaded')");
    $stmt->execute([$_SESSION['user_id']]);
    $activeDeliveries = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids b JOIN orders o ON b.order_id = o.id WHERE b.carrier_id = ? AND b.status = 'accepted' AND o.status = 'completed'");
    $stmt->execute([$_SESSION['user_id']]);
    $completedDeliveries = $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(b.price), 0) as total FROM bids b JOIN orders o ON b.order_id = o.id WHERE b.carrier_id = ? AND b.status = 'accepted' AND o.status = 'completed'");
    $stmt->execute([$_SESSION['user_id']]);
    $totalEarned = $stmt->fetch()['total'];

    $stmt = $pdo->prepare("SELECT o.*, b.status as bid_status FROM orders o JOIN bids b ON o.id = b.order_id WHERE b.carrier_id = ? AND b.status = 'accepted' AND o.status NOT IN ('completed','cancelled') ORDER BY o.created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $activeOrdersList = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT o.*, u.company_name, u.rating FROM orders o JOIN users u ON o.user_id = u.id WHERE o.status = 'new' ORDER BY o.created_at DESC LIMIT 5");
    $stmt->execute();
    $availableOrders = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND status = 'active' AND expires_at > NOW()");
    $stmt->execute([$_SESSION['user_id']]);
    $subscription = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM bids WHERE carrier_id = ? AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())");
    $stmt->execute([$_SESSION['user_id']]);
    $bidsThisMonth = $stmt->fetch()['cnt'];
}

$statusLabels = [
    'new' => 'Новый',
    'accepted' => 'Назначен',
    'pickup' => 'Погрузка',
    'loaded' => 'Загружен',
    'in_transit' => 'В пути',
    'arrived' => 'Прибыл',
    'unloaded' => 'Выгружен',
    'completed' => 'Завершён',
    'cancelled' => 'Отменён',
];

$statusIcons = [
    'new' => '🆕',
    'accepted' => '🤝',
    'pickup' => '🚛',
    'loaded' => '📦',
    'in_transit' => '🛣️',
    'arrived' => '📍',
    'unloaded' => '✅',
    'completed' => '🎉',
    'cancelled' => '❌',
];
?>

<style>
    .welcome-block {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .welcome-text h1 { margin-bottom: 4px; }
    .welcome-text p { color: var(--text-secondary); font-size: 14px; }
    .welcome-date {
        font-family: var(--font-mono);
        font-size: 13px;
        color: var(--text-secondary);
        background: var(--border-light);
        padding: 10px 18px;
    }
    .quick-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }
    .quick-action {
        flex: 1;
        min-width: 160px;
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 20px;
        text-decoration: none;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 14px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .quick-action:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-3px);
        border-color: var(--accent);
    }
    .quick-action .qa-icon {
        font-size: 28px;
        width: 50px;
        height: 50px;
        background: var(--border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .quick-action:hover .qa-icon { background: #fffbeb; }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 24px;
        transition: all 0.3s;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .stat-card .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }
    .stat-card .stat-icon { font-size: 24px; opacity: 0.8; }
    .stat-card .stat-value {
        font-size: 36px;
        font-weight: 900;
        letter-spacing: -1px;
        font-family: var(--font-mono);
        margin-bottom: 4px;
    }
    .stat-card .stat-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }
    .order-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-light);
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
    }
    .order-list-item:hover {
        padding-left: 8px;
        background: #fafafa;
        margin: 0 -8px;
        padding-right: 8px;
    }
    .order-list-item:last-child { border-bottom: none; }
    .order-list-item .order-info { flex: 1; min-width: 0; }
    .order-list-item .order-title {
        font-weight: 700;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .order-list-item .order-meta {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 2px;
    }
    .order-list-item .order-price {
        font-weight: 800;
        font-size: 16px;
        text-align: right;
        white-space: nowrap;
    }
    .empty-block {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }
    .empty-block .empty-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.6; }
    .empty-block h4 { margin-bottom: 6px; }
    .empty-block p { font-size: 13px; margin-bottom: 16px; }
    .sub-alert {
        background: #fffbeb;
        border-left: 3px solid var(--accent);
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sub-alert strong { font-size: 14px; }
    .sub-alert small { color: #92400e; }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
        .stat-card .stat-value { font-size: 28px; }
        .quick-action { min-width: 100%; }
    }
</style>

<!-- Приветствие -->
<div class="welcome-block">
    <div class="welcome-text">
        <h1>👋 Привет, <?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user_email'] ?? 'Пользователь') ?>!</h1>
        <p><?= $_SESSION['user_role'] === 'owner' ? 'Управляйте перевозками эффективно' : 'Находите выгодные заказы и зарабатывайте' ?></p>
    </div>
    <div class="welcome-date"><?= date('d.m.Y') ?> · <?= date('H:i') ?></div>
</div>

<!-- Предупреждение о верификации email -->
<?php 
// Проверяем статус верификации из БД
$stmt = $pdo->prepare("SELECT email_verified FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$verified = $stmt->fetch()['email_verified'];
$_SESSION['email_verified'] = (bool)$verified;

if (!$verified): 
?>
    <div class="alert" style="background:#fff3cd; color:#92400e; border-color:#f59e0b;">
        ⚠️ Подтвердите email! Письмо отправлено на <strong><?= $_SESSION['user_email'] ?></strong>. Проверьте папку «Спам».
    </div>
    <div class="alert" style="background:#fff3cd; color:#92400e; border-color:#f59e0b;">
        ⚠️ Подтвердите email! Письмо отправлено на <strong><?= $_SESSION['user_email'] ?></strong>. Проверьте папку «Спам».
    </div>
<?php endif; ?>

<!-- Предупреждение о подписке -->
<?php if ($_SESSION['user_role'] === 'carrier' && !$subscription): ?>
    <div class="sub-alert">
        <div>
            <strong>⚠️ Пробный период</strong><br>
            <small>Использовано откликов: <?= $bidsThisMonth ?> из 3 в этом месяце</small>
        </div>
        <a href="/pricing" class="btn btn-accent btn-sm">Оформить подписку</a>
    </div>
<?php endif; ?>

<!-- Быстрые действия -->
<div class="quick-actions">
    <?php if ($_SESSION['user_role'] === 'owner'): ?>
        <a href="/orders/create" class="quick-action">
            <span class="qa-icon">➕</span>
            <span>Создать заказ</span>
        </a>
    <?php endif; ?>
    <a href="/orders" class="quick-action">
        <span class="qa-icon">📋</span>
        <span><?= $_SESSION['user_role'] === 'owner' ? 'Мои заказы' : 'Все заказы' ?></span>
    </a>
    <a href="/orders/my" class="quick-action">
        <span class="qa-icon">📦</span>
        <span>Мои сделки</span>
    </a>
    <a href="/messages" class="quick-action">
        <span class="qa-icon">💬</span>
        <span>Сообщения</span>
    </a>
</div>

<!-- Статистика -->
<div class="stats-grid">
    <?php if ($_SESSION['user_role'] === 'owner'): ?>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">📋</span></div>
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Всего заказов</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">🔄</span></div>
            <div class="stat-value"><?= $activeOrders ?></div>
            <div class="stat-label">В работе</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">✅</span></div>
            <div class="stat-value"><?= $completedOrders ?></div>
            <div class="stat-label">Завершено</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">💰</span></div>
            <div class="stat-value"><?= number_format($totalSpent, 0, ',', ' ') ?> ₽</div>
            <div class="stat-label">Потрачено</div>
        </div>
    <?php else: ?>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">📋</span></div>
            <div class="stat-value"><?= $totalBids ?></div>
            <div class="stat-label">Откликов</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">🤝</span></div>
            <div class="stat-value"><?= $acceptedBids ?></div>
            <div class="stat-label">Получено заказов</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">🚛</span></div>
            <div class="stat-value"><?= $activeDeliveries ?></div>
            <div class="stat-label">В доставке</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span class="stat-icon">💰</span></div>
            <div class="stat-value"><?= number_format($totalEarned, 0, ',', ' ') ?> ₽</div>
            <div class="stat-label">Заработано</div>
        </div>
    <?php endif; ?>
</div>

<!-- Основной контент -->
<div class="dashboard-grid">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="margin:0;"><?= $_SESSION['user_role'] === 'owner' ? '📦 Активные заказы' : '🚛 Мои доставки' ?></h3>
            <a href="/orders/my" class="text-mono" style="font-size:12px;">Все →</a>
        </div>
        <?php if (empty($activeOrdersList)): ?>
            <div class="empty-block">
                <div class="empty-icon">📭</div>
                <h4>Нет активных заказов</h4>
                <p><?= $_SESSION['user_role'] === 'owner' ? 'Создайте новый заказ.' : 'Откликнитесь на заказы.' ?></p>
                <a href="<?= $_SESSION['user_role'] === 'owner' ? '/orders/create' : '/orders' ?>" class="btn btn-primary btn-sm">
                    <?= $_SESSION['user_role'] === 'owner' ? 'Создать заказ' : 'Найти заказы' ?>
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($activeOrdersList as $o): ?>
                <a href="/orders/view/<?= $o['id'] ?>" class="order-list-item">
                    <div class="order-info">
                        <div class="order-title"><?= htmlspecialchars($o['title']) ?></div>
                        <div class="order-meta">
                            <?= $statusIcons[$o['status']] ?? '📍' ?> <?= $statusLabels[$o['status']] ?? $o['status'] ?>
                            · <?= htmlspecialchars(mb_substr($o['pickup_address'], 0, 25)) ?> → <?= htmlspecialchars(mb_substr($o['delivery_address'], 0, 25)) ?>
                        </div>
                    </div>
                    <div class="order-price"><?= number_format($o['price'], 0, ',', ' ') ?> ₽</div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div>
        <?php if ($_SESSION['user_role'] === 'owner'): ?>
            <div class="card">
                <h3>📊 Сводка</h3>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border-light);">
                        <span style="color:var(--text-secondary); font-size:13px;">Новых заказов</span>
                        <span style="font-weight:800;"><?= $newOrders ?></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border-light);">
                        <span style="color:var(--text-secondary); font-size:13px;">Ждут откликов</span>
                        <span style="font-weight:800; color:var(--accent);"><?= $pendingBids ?></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border-light);">
                        <span style="color:var(--text-secondary); font-size:13px;">Доступно перевозчиков</span>
                        <span style="font-weight:800;"><?= $carriersCount ?></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:10px 0;">
                        <span style="color:var(--text-secondary); font-size:13px;">Завершено сделок</span>
                        <span style="font-weight:800; color:var(--success);"><?= $completedOrders ?></span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="margin:0;">🆕 Доступные заказы</h3>
                    <a href="/orders" class="text-mono" style="font-size:12px;">Все →</a>
                </div>
                <?php if (empty($availableOrders)): ?>
                    <div class="empty-block">
                        <div class="empty-icon">📭</div>
                        <h4>Заказов пока нет</h4>
                        <p>Новые заказы появляются здесь.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($availableOrders as $o): ?>
                        <a href="/orders/view/<?= $o['id'] ?>" class="order-list-item">
                            <div class="order-info">
                                <div class="order-title"><?= htmlspecialchars(mb_substr($o['title'], 0, 28)) ?></div>
                                <div class="order-meta">
                                    <?= htmlspecialchars($o['company_name']) ?>
                                    · <?= htmlspecialchars(mb_substr($o['pickup_address'], 0, 18)) ?>
                                </div>
                            </div>
                            <div class="order-price"><?= number_format($o['price'], 0, ',', ' ') ?> ₽</div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="card" style="margin-top:16px;">
                <h3>💎 Моя подписка</h3>
                <?php if ($subscription): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span class="badge badge-success">Активна</span>
                            <br><small style="color:var(--text-secondary);">до <?= date('d.m.Y', strtotime($subscription['expires_at'])) ?></small>
                        </div>
                        <span style="font-weight:800; text-transform:uppercase;"><?= $subscription['plan'] ?></span>
                    </div>
                <?php else: ?>
                    <p style="color:var(--text-secondary); font-size:13px; margin-bottom:12px;">
                        Бесплатный тариф: 3 отклика в месяц (<?= $bidsThisMonth ?> использовано)
                    </p>
                    <a href="/pricing" class="btn btn-accent btn-sm btn-block">Перейти на Pro</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>