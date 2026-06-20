<?php require_once BASE_PATH . '/templates/header.php'; ?>

<div class="card">
    <h2><?= htmlspecialchars($order['title']) ?></h2>
    <p style="color:#666;">Статус: 
        <?php 
        $statuses = ['new' => 'Новый', 'in_progress' => 'В работе', 'delivered' => 'Доставлен', 'completed' => 'Завершён'];
        echo $statuses[$order['status']] ?? $order['status'];
        ?>
    </p>
    
    <table style="width:100%; margin:20px 0;">
        <tr><td style="padding:5px 10px;"><strong>Заказчик:</strong></td><td><?= htmlspecialchars($order['company_name'] ?: $order['name']) ?></td></tr>
        <tr><td style="padding:5px 10px;"><strong>Телефон:</strong></td><td><?= htmlspecialchars($order['phone']) ?></td></tr>
        <tr><td style="padding:5px 10px;"><strong>Груз:</strong></td><td><?= htmlspecialchars($order['cargo_type']) ?>, <?= $order['weight'] ?> т, <?= $order['volume'] ?> м³</td></tr>
        <tr><td style="padding:5px 10px;"><strong>Маршрут:</strong></td><td><?= htmlspecialchars($order['pickup_address']) ?> → <?= htmlspecialchars($order['delivery_address']) ?></td></tr>
        <tr><td style="padding:5px 10px;"><strong>Даты:</strong></td><td>📅 <?= $order['pickup_date'] ?> — <?= $order['delivery_date'] ?></td></tr>
        <tr><td style="padding:5px 10px;"><strong>Бюджет:</strong></td><td style="font-size:20px; font-weight:bold; color:#28a745;"><?= number_format($order['price'], 0, ',', ' ') ?> ₽</td></tr>
    </table>
    
    <!-- Документы (если сделка в работе) -->
    <?php if ($order['status'] != 'new'): ?>
        <div style="margin:20px 0; padding:15px; background:#fff3cd; border-radius:6px;">
            <strong>📄 Документы по сделке:</strong>
            <a href="/documents/view/<?= $order['id'] ?>?type=contract" class="btn btn-sm btn-primary" target="_blank">Договор-заявка</a>
            <a href="/documents/view/<?= $order['id'] ?>?type=waybill" class="btn btn-sm btn-primary" target="_blank">Транспортная накладная</a>
        </div>
    <?php endif; ?>
</div>

<!-- Для перевозчика: форма отклика -->
<?php if ($_SESSION['user_role'] === 'carrier' && !$myBid && $order['status'] === 'new'): ?>
    <div class="card">
        <h3>Предложить свою цену</h3>
        <form method="POST" action="/bids/place/<?= $order['id'] ?>">
            <div class="form-group">
                <label>Ваша цена (₽)</label>
                <input type="number" name="price" required placeholder="Например: 45000">
            </div>
            <button type="submit" class="btn btn-success">Отправить отклик</button>
        </form>
    </div>
<?php endif; ?>

<?php if ($myBid): ?>
    <div class="alert alert-success">
        Вы уже отправили отклик на <?= number_format($myBid['price'], 0, ',', ' ') ?> ₽. Статус: <?= $myBid['status'] ?>
    </div>
<?php endif; ?>

<!-- Для грузовладельца: список откликов -->
<?php if ($_SESSION['user_id'] == $order['user_id'] && !empty($bids)): ?>
    <div class="card">
        <h3>Отклики перевозчиков (<?= count($bids) ?>)</h3>
        <?php foreach ($bids as $bid): ?>
            <div style="border:1px solid #ddd; padding:15px; margin:10px 0; border-radius:6px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <strong><?= htmlspecialchars($bid['company_name']) ?></strong> | Рейтинг: <?= $bid['rating'] ?>
                        <br>Цена: <span style="font-size:18px; font-weight:bold; color:#28a745;"><?= number_format($bid['price'], 0, ',', ' ') ?> ₽</span>
                        <br>Статус: <?= $bid['status'] ?>
                    </div>
                    <?php if ($bid['status'] === 'pending' && $order['status'] === 'new'): ?>
                        <form method="POST" action="/bids/accept">
                            <input type="hidden" name="bid_id" value="<?= $bid['id'] ?>">
                            <button type="submit" class="btn btn-success">Выбрать перевозчика</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<a href="/orders" class="btn" style="background:#6c757d; color:white; margin-top:10px;">← К списку заказов</a>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>