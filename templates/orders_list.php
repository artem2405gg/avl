<?php require_once BASE_PATH . '/templates/header.php'; ?>

<h2>Доступные заказы</h2>

<!-- Форма поиска -->
<div class="card">
    <form method="GET" action="/orders">
        <div class="grid-2">
            <div class="form-group">
                <label>Город погрузки</label>
                <input type="text" name="city_from" value="<?= htmlspecialchars($_GET['city_from'] ?? '') ?>" placeholder="Например: Москва">
            </div>
            <div class="form-group">
                <label>Город выгрузки</label>
                <input type="text" name="city_to" value="<?= htmlspecialchars($_GET['city_to'] ?? '') ?>" placeholder="Например: Казань">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Найти</button>
        <a href="/orders" class="btn" style="background:#6c757d; color:white;">Сбросить</a>
    </form>
</div>

<?php if (empty($orders)): ?>
    <div class="card" style="text-align:center; padding:40px;">
        <h3>Заказов пока нет</h3>
        <p>Станьте первым грузовладельцем!</p>
        <a href="/orders/create" class="btn btn-primary">Создать заказ</a>
    </div>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <h3><?= htmlspecialchars($order['title']) ?></h3>
                    <p style="color:#666;">
                        <?= htmlspecialchars($order['pickup_address']) ?> → <?= htmlspecialchars($order['delivery_address']) ?>
                    </p>
                    <p>
                        <?= htmlspecialchars($order['cargo_type']) ?> | 
                        <?= $order['weight'] ?> т | 
                        <?= $order['volume'] ?> м³
                    </p>
                    <p style="color:#666;">
                        📅 Погрузка: <?= $order['pickup_date'] ?> | Доставка: <?= $order['delivery_date'] ?>
                    </p>
                    <p style="font-size:12px; color:#999;">
                        Заказчик: <?= htmlspecialchars($order['company_name']) ?> | Рейтинг: <?= $order['rating'] ?>
                    </p>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:24px; font-weight:bold; color:#28a745; margin-bottom:10px;">
                        <?= number_format($order['price'], 0, ',', ' ') ?> ₽
                    </div>
                    <a href="/orders/view/<?= $order['id'] ?>" class="btn btn-primary">Подробнее</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>