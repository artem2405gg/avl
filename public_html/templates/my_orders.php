<?php require_once BASE_PATH . '/templates/header.php'; ?>

<h2>Мои сделки</h2>

<?php if (empty($orders)): ?>
    <div class="card" style="text-align:center; padding:40px;">
        <h3>Сделок пока нет</h3>
        <?php if ($_SESSION['user_role'] === 'owner'): ?>
            <a href="/orders/create" class="btn btn-primary">Создать первый заказ</a>
        <?php else: ?>
            <a href="/orders" class="btn btn-primary">Найти заказы</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="card">
            <h3><?= htmlspecialchars($order['title']) ?></h3>
            <p>
                <?= htmlspecialchars($order['pickup_address']) ?> → <?= htmlspecialchars($order['delivery_address']) ?>
            </p>
            <p>
                Статус: <strong><?= $order['status'] ?></strong> | 
                <?= number_format($order['price'], 0, ',', ' ') ?> ₽
            </p>
            <a href="/orders/view/<?= $order['id'] ?>" class="btn btn-sm btn-primary">Подробнее</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>