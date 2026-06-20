<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .admin-tabs { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
    .admin-tabs a {
        padding: 8px 18px; border-radius: 8px; text-decoration: none;
        font-weight: 500; font-size: 14px; color: #6b7280;
        background: white; border: 1px solid #e5e7eb; transition: all 0.2s;
    }
    .admin-tabs a:hover { background: #f3f4f6; }
    .admin-tabs a.active { background: #000; color: white; border-color: #000; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .stat-mini { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
    .stat-mini-item { text-align: center; padding: 16px; background: var(--surface); border: 1px solid var(--border); flex: 1; min-width: 100px; }
    .stat-mini-item .value { font-size: 24px; font-weight: 900; font-family: var(--font-mono); }
    .stat-mini-item .label { font-size: 10px; color: var(--text-secondary); text-transform: uppercase; margin-top: 4px; }
    .badge-status { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .badge-admin { background: #fef3c7; color: #92400e; }
    .badge-warning { background: #fffbeb; color: #92400e; }
    .badge-success { background: #f0fdf4; color: #166534; }
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th { background: #f9fafb; padding: 10px 14px; text-align: left; font-size: 11px; text-transform: uppercase; color: #6b7280; font-weight: 700; }
    .admin-table td { padding: 10px 14px; border-top: 1px solid #f0f0f0; font-size: 13px; }
    .admin-table tr:hover { background: #f9fafb; }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
</style>

<a href="/admin/users" style="color:var(--primary);">← К пользователям</a>

<h1 style="margin-top:12px;">👤 <?= htmlspecialchars($user['name']) ?></h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets">🎧 Тикеты</a>
</div>

<!-- Статистика -->
<div class="stat-mini">
    <?php if ($user['role'] == 'owner'): ?>
        <div class="stat-mini-item"><div class="value"><?= $totalOrders ?></div><div class="label">Заказов</div></div>
        <div class="stat-mini-item"><div class="value"><?= $completedOrders ?></div><div class="label">Завершено</div></div>
        <div class="stat-mini-item"><div class="value"><?= number_format($totalAmount, 0, ',', ' ') ?> ₽</div><div class="label">На сумму</div></div>
    <?php else: ?>
        <div class="stat-mini-item"><div class="value"><?= $totalBids ?></div><div class="label">Откликов</div></div>
        <div class="stat-mini-item"><div class="value"><?= $acceptedBids ?></div><div class="label">Принято</div></div>
        <div class="stat-mini-item"><div class="value"><?= number_format($totalEarned, 0, ',', ' ') ?> ₽</div><div class="label">Заработано</div></div>
    <?php endif; ?>
    <div class="stat-mini-item"><div class="value">⭐ <?= $user['rating'] ?></div><div class="label">Рейтинг</div></div>
    <div class="stat-mini-item"><div class="value"><?= $refCount ?></div><div class="label">Рефералов</div></div>
</div>

<div class="info-grid">
    <!-- Информация -->
    <div class="card">
        <h3>📋 Профиль</h3>
        <table class="info-table">
            <tr><td>ID</td><td>#<?= $user['id'] ?></td></tr>
            <tr><td>Имя</td><td><?= htmlspecialchars($user['name']) ?></td></tr>
            <tr><td>Email</td><td><?= htmlspecialchars($user['email']) ?> <?= $user['email_verified'] ? '✅' : '⚠️' ?></td></tr>
            <tr><td>Телефон</td><td><?= htmlspecialchars($user['phone'] ?: '—') ?></td></tr>
            <tr><td>Роль</td><td><span class="badge-status <?= $user['role'] == 'owner' ? 'badge-owner' : 'badge-carrier' ?>"><?= $user['role'] == 'owner' ? 'Грузовладелец' : 'Перевозчик' ?></span></td></tr>
            <tr><td>Компания</td><td><?= htmlspecialchars($user['company_name'] ?: '—') ?></td></tr>
            <tr><td>ИНН</td><td><?= htmlspecialchars($user['inn'] ?: '—') ?></td></tr>
            <tr><td>Рейтинг</td><td>⭐ <?= $user['rating'] ?></td></tr>
            <tr><td>Бонусных дней</td><td><?= $user['bonus_days'] ?></td></tr>
            <tr><td>Админ</td><td><?= $user['is_admin'] ? '✅ Да' : '❌ Нет' ?></td></tr>
            <tr><td>Дата регистрации</td><td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td></tr>
            <tr><td>Последняя активность</td><td><?= $user['last_activity'] ? date('d.m.Y H:i', strtotime($user['last_activity'])) : 'Нет данных' ?></td></tr>
        </table>
        
        <?php if ($user['bank_details']): ?>
            <h4 style="margin-top:16px;">💰 Реквизиты</h4>
            <pre style="background:#f9fafb; padding:12px; border-radius:8px; font-size:13px;"><?= htmlspecialchars($user['bank_details']) ?></pre>
        <?php endif; ?>
        
        <?php if ($user['ref_code']): ?>
            <p style="margin-top:8px;"><strong>Реф. код:</strong> <?= $user['ref_code'] ?></p>
            <p><strong>Реф. ссылка:</strong> <?= SITE_URL ?>/r/<?= $user['ref_code'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Подписки -->
    <div class="card">
        <h3>💎 Подписки</h3>
        <?php if (empty($subscriptions)): ?>
            <p style="color:var(--text-secondary);">Нет подписок</p>
        <?php else: ?>
            <?php foreach ($subscriptions as $sub): ?>
                <div style="border:1px solid var(--border); padding:10px; margin:6px 0; border-radius:8px;">
                    <strong><?= $sub['plan'] ?></strong> — 
                    <span class="badge-status <?= $sub['status'] == 'active' ? 'badge-success' : 'badge-admin' ?>"><?= $sub['status'] ?></span>
                    <br><small>До: <?= $sub['expires_at'] ? date('d.m.Y', strtotime($sub['expires_at'])) : '—' ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Заказы -->
<div class="card">
    <h3>📦 <?= $user['role'] == 'owner' ? 'Заказы' : 'Сделки' ?></h3>
    <?php if (empty($orders)): ?>
        <p style="color:var(--text-secondary);">Нет данных</p>
    <?php else: ?>
        <table class="admin-table">
            <tr><th>ID</th><th>Название</th><th>Маршрут</th><th>Сумма</th><th>Статус</th><th>Дата</th></tr>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><a href="/admin/order/<?= $o['id'] ?>" style="color:#4361ee;"><?= htmlspecialchars(mb_substr($o['title'], 0, 30)) ?></a></td>
                <td><small><?= htmlspecialchars(mb_substr($o['pickup_address'], 0, 15)) ?> → <?= htmlspecialchars(mb_substr($o['delivery_address'], 0, 15)) ?></small></td>
                <td><?= number_format($o['price'], 0, ',', ' ') ?> ₽</td>
                <td><span class="badge-status badge-admin"><?= $o['status'] ?></span></td>
                <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<!-- Отзывы -->
<?php if (!empty($reviews)): ?>
<div class="card">
    <h3>⭐ Отзывы (<?= count($reviews) ?>)</h3>
    <?php foreach ($reviews as $r): ?>
        <div style="border-bottom:1px solid var(--border-light); padding:8px 0;">
            <strong><?= htmlspecialchars($r['reviewer_name']) ?></strong> — <?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?>
            <br><small><?= htmlspecialchars($r['comment']) ?></small>
            <br><small style="color:var(--text-secondary);"><?= date('d.m.Y', strtotime($r['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Счета -->
<?php if (!empty($invoices)): ?>
<div class="card">
    <h3>🧾 Счета</h3>
    <table class="admin-table">
        <tr><th>ID</th><th>Тариф</th><th>Сумма</th><th>Статус</th><th>Дата</th></tr>
        <?php foreach ($invoices as $inv): ?>
        <tr>
            <td>#<?= $inv['id'] ?></td>
            <td><?= $inv['plan'] ?></td>
            <td><?= number_format($inv['amount'], 0, ',', ' ') ?> ₽</td>
            <td><span class="badge-status <?= $inv['status'] == 'paid' ? 'badge-success' : ($inv['status'] == 'pending' ? 'badge-warning' : 'badge-admin') ?>"><?= $inv['status'] ?></span></td>
            <td><?= date('d.m.Y', strtotime($inv['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<!-- Рефералы -->
<?php if (!empty($refUsers)): ?>
<div class="card">
    <h3>🤝 Приглашённые пользователи (<?= $refCount ?>)</h3>
    <table class="admin-table">
        <tr><th>Имя</th><th>Email</th><th>Дата</th></tr>
        <?php foreach ($refUsers as $ru): ?>
        <tr>
            <td><?= htmlspecialchars($ru['name']) ?></td>
            <td><?= htmlspecialchars($ru['email']) ?></td>
            <td><?= date('d.m.Y', strtotime($ru['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<!-- Тикеты -->
<?php if (!empty($tickets)): ?>
<div class="card">
    <h3>🎧 Тикеты</h3>
    <?php foreach ($tickets as $t): ?>
        <div style="border:1px solid var(--border); padding:10px; margin:6px 0; border-radius:8px;">
            <strong><?= htmlspecialchars($t['subject']) ?></strong>
            <span class="badge-status <?= $t['status'] == 'open' ? 'badge-warning' : 'badge-success' ?>"><?= $t['status'] ?></span>
            <br><small><?= date('d.m.Y', strtotime($t['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>