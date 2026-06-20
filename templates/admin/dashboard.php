<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .admin-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .admin-tabs a {
        padding: 8px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        color: #6b7280;
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    .admin-tabs a:hover { background: #f3f4f6; }
    .admin-tabs a.active { background: #000; color: white; border-color: #000; }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 20px;
    }
    .stat-card .stat-value {
        font-size: 32px;
        font-weight: 900;
        font-family: var(--font-mono);
    }
    .stat-card .stat-label {
        font-size: 11px;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-top: 4px;
    }
    .stat-card .stat-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    .admin-table th {
        background: #f9fafb;
        padding: 10px 14px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 700;
    }
    .admin-table td {
        padding: 10px 14px;
        border-top: 1px solid #f0f0f0;
        font-size: 13px;
    }
    .admin-table tr:hover { background: #f9fafb; }
    
    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .badge-admin { background: #fef3c7; color: #92400e; }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<h1>🛡️ Админ-панель</h1>

<div class="admin-tabs">
    <a href="/admin" class="active">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets">🎧 Тикеты</a>
    <a href="/admin/mailing">📢 Рассылка</a>
    <a href="/dashboard">← На сайт</a>
</div>

<!-- Статистика -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?= $stats['users'] ?></div>
        <div class="stat-label">Всего пользователей</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-value"><?= $stats['owners'] ?></div>
        <div class="stat-label">Грузовладельцев</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🚛</div>
        <div class="stat-value"><?= $stats['carriers'] ?></div>
        <div class="stat-label">Перевозчиков</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-value"><?= $stats['orders'] ?></div>
        <div class="stat-label">Всего заказов</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= $stats['completed'] ?></div>
        <div class="stat-label">Завершённых</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🔄</div>
        <div class="stat-value"><?= $stats['active'] ?></div>
        <div class="stat-label">Активных</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💬</div>
        <div class="stat-value"><?= $stats['messages'] ?></div>
        <div class="stat-label">Сообщений</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🧾</div>
        <div class="stat-value"><?= $stats['pendingInvoices'] ?></div>
        <div class="stat-label">Счетов ожидают</div>
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
                    <strong><?= htmlspecialchars($u['name']) ?></strong>
                    <br><small style="color:#6b7280;"><?= htmlspecialchars($u['email']) ?></small>
                </td>
                <td>
                    <span class="badge-status <?= $u['role'] === 'owner' ? 'badge-owner' : 'badge-carrier' ?>">
                        <?= $u['role'] === 'owner' ? 'Владелец' : 'Перевозчик' ?>
                    </span>
                    <?php if ($u['is_admin']): ?>
                        <span class="badge-status badge-admin">Админ</span>
                    <?php endif; ?>
                </td>
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
                    <strong><?= htmlspecialchars(mb_substr($o['title'], 0, 30)) ?></strong>
                    <br><small style="color:#6b7280;"><?= htmlspecialchars($o['owner_name']) ?></small>
                </td>
                <td>
                    <?php
                    $statusLabels = [
                        'new' => 'Новый',
                        'accepted' => 'Принят',
                        'pickup' => 'Погрузка',
                        'loaded' => 'Загружен',
                        'in_transit' => 'В пути',
                        'arrived' => 'Прибыл',
                        'unloaded' => 'Выгружен',
                        'completed' => 'Завершён',
                        'cancelled' => 'Отменён',
                    ];
                    $statusClass = in_array($o['status'], ['completed']) ? 'badge-carrier' : (in_array($o['status'], ['new']) ? 'badge-owner' : 'badge-admin');
                    ?>
                    <span class="badge-status <?= $statusClass ?>">
                        <?= $statusLabels[$o['status']] ?? $o['status'] ?>
                    </span>
                </td>
                <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="/admin/orders" style="margin-top:12px; display:inline-block;">Все заказы →</a>
    </div>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>