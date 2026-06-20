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
    .admin-search { display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
    .admin-search input { padding: 10px 14px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
    .badge-status { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .badge-admin { background: #fef3c7; color: #92400e; }
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .btn-xs { padding: 5px 10px; font-size: 11px; border-radius: 6px; }
</style>

<h1>👥 Пользователи</h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users" class="active">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets">🎧 Тикеты</a>
    <a href="/dashboard">← На сайт</a>
</div>

<div class="card">
    <form class="admin-search" method="GET" action="/admin/users">
        <input type="text" name="search" placeholder="Поиск по имени или email..." value="<?= htmlspecialchars($s ?? '') ?>">
        <button type="submit" class="btn btn-primary btn-sm">Найти</button>
    </form>

    <table class="admin-table">
        <tr><th>ID</th><th>Пользователь</th><th>Роль</th><th>Компания</th><th>Дата</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td>#<?= $u['id'] ?></td>
            <td>
                <a href="/admin/user/<?= $u['id'] ?>" style="color:#4361ee; text-decoration:none;">
                    <strong><?= htmlspecialchars($u['name']) ?></strong>
                </a>
                <br><small><?= htmlspecialchars($u['email']) ?></small>
                <?php if ($u['is_admin']): ?><span class="badge-status badge-admin">Админ</span><?php endif; ?>
            </td>
            <td><span class="badge-status <?= $u['role'] === 'owner' ? 'badge-owner' : 'badge-carrier' ?>"><?= $u['role'] === 'owner' ? 'Владелец' : 'Перевозчик' ?></span></td>
            <td><?= htmlspecialchars($u['company_name'] ?: '—') ?></td>
            <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>