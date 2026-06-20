<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .admin-tabs { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
    .admin-tab { padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; color: #6b7280; background: white; border: 1px solid #e5e7eb; transition: all 0.2s; }
    .admin-tab:hover { background: #f3f4f6; }
    .admin-tab.active { background: #4361ee; color: white; border-color: #4361ee; }
    .admin-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; }
    .admin-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: 600; }
    .admin-table td { padding: 12px 16px; border-top: 1px solid #f0f0f0; font-size: 14px; }
    .admin-table tr:hover { background: #f9fafb; }
    .admin-search { display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
    .admin-search input, .admin-search select { padding: 10px 14px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
    .admin-search button { padding: 10px 20px; }
    .badge-status { padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .badge-admin { background: #fef3c7; color: #92400e; }
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .btn-xs { padding: 5px 10px; font-size: 11px; border-radius: 6px; }
</style>

<h1>🛡️ Админ-панель</h1>

<div class="admin-tabs">
    <a href="/admin" class="admin-tab active">📊 Дашборд</a>
    <a href="/admin/users" class="admin-tab">👥 Пользователи</a>
    <a href="/admin/orders" class="admin-tab">📦 Заказы</a>
    <a href="/dashboard" class="admin-tab">← На сайт</a>
</div>

<!-- Статистика -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">👥</div>
        <div class="stat-value"><?= $stats['users'] ?></div>
        <div class="stat-label">Всего пользователей</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">📋</div>
        <div class="stat-value"><?= $stats['owners'] ?></div>
        <div class="stat-label">Грузовладельцев</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple">🚛</div>
        <div class="stat-value"><?= $stats['carriers'] ?></div>
        <div class="stat-label">Перевозчиков</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-orange">📦</div>
        <div class="stat-value"><?= $stats['orders'] ?></div>
        <div class="stat-label">Всего заказов</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div class="stat-value"><?= $stats['completed'] ?></div>
        <div class="stat-label">Завершённых</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue">🆕</div>
        <div class="stat-value"><?= $stats['active'] ?></div>
        <div class="stat-label">Активных</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple">💬</div>
        <div class="stat-value"><?= $stats['messages'] ?></div>
        <div class="stat-label">Сообщений</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-orange">💰</div>
        <div class="stat-value">0 ₽</div>
        <div class="stat-label">Доход (скоро)</div>
    </div>
</div>

<div class="grid-2">
    <!-- Последние пользователи -->
    <div class="card">
        <h3>👥 Новые пользователи</h3>
        <table class="admin-table">
            <tr><th>Пользователь</th><th>Роль</th><th>Дата</th></tr>
            <?php foreach ($latestUsers as $u): ?>
            <tr>
                <td>
                    <a href="/admin/user/<?= $u['id'] ?>" style="color:#4361ee; text-decoration:none;">
                        <?= htmlspecialchars($u['name']) ?>
                    </a>
                    <br><small style="color:#6b7280;"><?= htmlspecialchars($u['email']) ?></small>
                </td>
                <td><span class="badge-status <?= $u['role'] === 'owner' ? 'badge-owner' : 'badge-carrier' ?>"><?= $u['role'] === 'owner' ? 'Владелец' : 'Перевозчик' ?></span></td>
                <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="/admin/users" style="margin-top:12px; display:inline-block;">Все пользователи →</a>
    </div>

    <!-- Последние заказы -->
    <div class="card">
        <h3>📦 Новые заказы</h3>
        <table class="admin-table">
            <tr><th>Заказ</th><th>Статус</th><th>Дата</th></tr>
            <?php foreach ($latestOrders as $o): ?>
            <tr>
                <td>
                    <a href="/admin/order/<?= $o['id'] ?>" style="color:#4361ee; text-decoration:none;">
                        <?= htmlspecialchars(mb_substr($o['title'], 0, 30)) ?>
                    </a>
                    <br><small style="color:#6b7280;"><?= htmlspecialchars($o['owner_name']) ?></small>
                </td>
                <td><span class="badge-status badge-<?= $o['status'] === 'new' ? 'owner' : ($o['status'] === 'completed' ? 'carrier' : 'admin') ?>"><?= $o['status'] ?></span></td>
                <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="/admin/orders" style="margin-top:12px; display:inline-block;">Все заказы →</a>
    </div>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>