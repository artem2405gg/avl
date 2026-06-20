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
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .badge-admin { background: #fef3c7; color: #92400e; }
</style>

<h1>📦 Заказы</h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders" class="active">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets">🎧 Тикеты</a>
    <a href="/dashboard">← На сайт</a>
</div>

<div class="card">
    <table class="admin-table">
        <tr><th>ID</th><th>Заказ</th><th>Заказчик</th><th>Цена</th><th>Статус</th><th>Дата</th></tr>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= $o['id'] ?></td>
            <td><a href="/admin/order/<?= $o['id'] ?>" style="color:#4361ee; text-decoration:none;"><?= htmlspecialchars(mb_substr($o['title'], 0, 40)) ?></a></td>
            <td><?= htmlspecialchars($o['owner_name']) ?></td>
            <td><?= number_format($o['price'], 0, ',', ' ') ?> ₽</td>
            <td><span class="badge-status <?= $o['status'] == 'completed' ? 'badge-carrier' : ($o['status'] == 'new' ? 'badge-owner' : 'badge-admin') ?>"><?= $o['status'] ?></span></td>
            <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>