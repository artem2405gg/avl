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
    .admin-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; }
    .admin-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-size: 11px; text-transform: uppercase; color: #6b7280; font-weight: 700; }
    .admin-table td { padding: 12px 16px; border-top: 1px solid #f0f0f0; font-size: 14px; }
    .admin-table tr:hover { background: #f9fafb; }
    .badge-status { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .badge-warning { background: #fffbeb; color: #92400e; }
    .badge-success { background: #f0fdf4; color: #166534; }
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .badge-admin { background: #fef3c7; color: #92400e; }
    .btn-xs { padding: 6px 12px; font-size: 11px; border-radius: 6px; }
    .msg-bubble {
        padding: 14px; border-radius: 8px; margin: 10px 0;
        border-left: 3px solid var(--border);
    }
    .msg-bubble.admin { background: #f0fdf4; border-color: var(--success); }
    .msg-bubble.user { background: #f9fafb; border-color: var(--border); }
    .msg-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
</style>

<h1>🎧 Тикеты техподдержки</h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets" class="active">🎧 Тикеты</a>
    <a href="/dashboard">← На сайт</a>
</div>

<?php if (empty($tickets)): ?>
    <div class="card" style="text-align:center; padding:40px;">Тикетов пока нет</div>
<?php else: ?>
    <?php foreach ($tickets as $t): ?>
        <div class="card" style="<?= $t['status'] == 'open' ? 'border-left:3px solid var(--accent);' : '' ?>">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <strong>#<?= $t['id'] ?> <?= htmlspecialchars($t['subject']) ?></strong>
                    <br><small><?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['email']) ?>)</small>
                    <br><small style="color:var(--text-secondary);"><?= date('d.m.Y H:i', strtotime($t['created_at'])) ?></small>
                </div>
                <div style="text-align:right;">
                    <span class="badge-status <?= $t['status'] == 'open' ? 'badge-warning' : 'badge-success' ?>">
                        <?= $t['status'] == 'open' ? 'Открыт' : 'Закрыт' ?>
                    </span>
                    <?php if ($t['status'] == 'open'): ?>
                        <br><a href="/admin/ticket/<?= $t['id'] ?>" class="btn btn-sm btn-primary" style="margin-top:8px;">Ответить</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>