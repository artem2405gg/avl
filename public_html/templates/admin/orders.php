<?php require_once BASE_PATH . '/templates/header.php']; ?>

<h1>📦 Заказы</h1>

<div class="admin-tabs">
    <a href="/admin" class="admin-tab">📊 Дашборд</a>
    <a href="/admin/users" class="admin-tab">👥 Пользователи</a>
    <a href="/admin/orders" class="admin-tab active">📦 Заказы</a>
</div>

<div class="card">
    <form class="admin-search" method="GET" action="/admin/orders">
        <select name="status">
            <option value="">Все статусы</option>
            <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>Новые</option>
            <option value="in_progress" <?= $status === 'in_progress' ? 'selected' : '' ?>>В работе</option>
            <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Завершённые</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Фильтр</button>
    </form>

    <table class="admin-table">
        <tr>
            <th>ID</th><th>Заказ</th><th>Заказчик</th><th>Цена</th><th>Статус</th><th>Дата</th><th>Действия</th>
        </tr>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= htmlspecialchars(mb_substr($o['title'], 0, 40)) ?></td>
            <td><?= htmlspecialchars($o['owner_company'] ?: $o['owner_name']) ?></td>
            <td><?= number_format($o['price'], 0, ',', ' ') ?> ₽</td>
            <td><span class="badge-status badge-<?= $o['status'] === 'new' ? 'owner' : ($o['status'] === 'completed' ? 'carrier' : 'admin') ?>"><?= $o['status'] ?></span></td>
            <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
            <td>
                <a href="/admin/order/<?= $o['id'] ?>" class="btn btn-outline btn-xs">🔍</a>
                <form method="POST" action="/admin/delete_order/<?= $o['id'] ?>" style="display:inline;" onsubmit="return confirm('Удалить заказ?')">
                    <button class="btn btn-danger btn-xs">🗑️</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once BASE_PATH . '/templates/footer.php']; ?>