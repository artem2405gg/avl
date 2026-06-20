<?php require_once BASE_PATH . '/templates/header.php']; ?>

<a href="/admin/orders" style="color:#4361ee;">← Назад к заказам</a>

<div class="card" style="margin-top:16px;">
    <h2>Заказ #<?= $order['id'] ?>: <?= htmlspecialchars($order['title']) ?></h2>
    <table class="info-table">
        <tr><td>Заказчик</td><td><?= htmlspecialchars($order['owner_name']) ?> (<?= htmlspecialchars($order['owner_email']) ?>)</td></tr>
        <tr><td>Груз</td><td><?= htmlspecialchars($order['cargo_type']) ?>, <?= $order['weight'] ?> т</td></tr>
        <tr><td>Маршрут</td><td><?= htmlspecialchars($order['pickup_address']) ?> → <?= htmlspecialchars($order['delivery_address']) ?></td></tr>
        <tr><td>Даты</td><td><?= $order['pickup_date'] ?> — <?= $order['delivery_date'] ?></td></tr>
        <tr><td>Бюджет</td><td><?= number_format($order['price'], 0, ',', ' ') ?> ₽</td></tr>
        <tr><td>Статус</td><td><?= $order['status'] ?></td></tr>
        <tr><td>Создан</td><td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td></tr>
    </table>
</div>

<h3>Отклики перевозчиков</h3>
<?php if (empty($bids)): ?>
    <div class="card"><p>Откликов пока нет</p></div>
<?php else: ?>
    <?php foreach ($bids as $bid): ?>
    <div class="card">
        <strong><?= htmlspecialchars($bid['company_name'] ?: $bid['name']) ?></strong>
        <br>Цена: <?= number_format($bid['price'], 0, ',', ' ') ?> ₽
        <br>Статус: <span class="badge-status badge-<?= $bid['status'] === 'accepted' ? 'carrier' : 'admin' ?>"><?= $bid['status'] ?></span>
        <br><small><?= htmlspecialchars($bid['email']) ?></small>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php']; ?>