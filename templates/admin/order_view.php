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
    .badge-status { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .badge-owner { background: #eef2ff; color: #4361ee; }
    .badge-carrier { background: #ecfdf5; color: #059669; }
    .badge-admin { background: #fef3c7; color: #92400e; }
    .timeline { position: relative; padding-left: 30px; }
    .timeline::before { content: ''; position: absolute; left: 12px; top: 0; bottom: 0; width: 2px; background: #e5e7eb; }
    .timeline-item { position: relative; margin-bottom: 14px; }
    .timeline-dot { position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #4361ee; }
    .chat-msg { padding: 10px 14px; border-radius: 12px; margin: 6px 0; max-width: 70%; }
    .chat-msg.owner { background: #eef2ff; margin-right: auto; }
    .chat-msg.carrier { background: #ecfdf5; margin-left: auto; }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
</style>

<a href="/admin/orders" style="color:var(--primary);">← К заказам</a>

<h1 style="margin-top:12px;">Заказ #<?= $order['id'] ?>: <?= htmlspecialchars($order['title']) ?></h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets">🎧 Тикеты</a>
</div>

<div class="info-grid">
    <!-- Информация о заказе -->
    <div class="card">
        <h3>📋 Информация о заказе</h3>
        <table class="info-table">
            <tr><td>Статус</td><td><span class="badge-status badge-admin"><?= $order['status'] ?></span></td></tr>
            <tr><td>Груз</td><td><?= htmlspecialchars($order['cargo_type']) ?>, <?= $order['weight'] ?> т, <?= $order['volume'] ?> м³</td></tr>
            <tr><td>Маршрут</td><td><?= htmlspecialchars($order['pickup_address']) ?> → <?= htmlspecialchars($order['delivery_address']) ?></td></tr>
            <tr><td>Даты</td><td><?= $order['pickup_date'] ?> — <?= $order['delivery_date'] ?></td></tr>
            <tr><td>Бюджет</td><td style="font-weight:700; color:#10b981;"><?= number_format($order['price'], 0, ',', ' ') ?> ₽</td></tr>
            <?php if ($carrier): ?>
            <tr><td>Цена перевозки</td><td style="font-weight:700;"><?= number_format($carrier['bid_price'], 0, ',', ' ') ?> ₽</td></tr>
            <?php endif; ?>
            <tr><td>Оплата</td><td><?= $order['payment_status'] ?? 'Не начата' ?></td></tr>
            <tr><td>Создан</td><td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td></tr>
        </table>
    </div>

    <!-- Участники -->
    <div class="card">
        <h3>👥 Участники</h3>
        
        <h4>📦 Грузовладелец</h4>
        <p><strong><?= htmlspecialchars($order['owner_company'] ?: $order['owner_name']) ?></strong></p>
        <p>📧 <?= htmlspecialchars($order['owner_email']) ?></p>
        <p>📞 <?= htmlspecialchars($order['owner_phone']) ?></p>
        <p>⭐ <?= $order['owner_rating'] ?></p>
        
        <?php if ($carrier): ?>
            <h4 style="margin-top:16px;">🚛 Перевозчик</h4>
            <p><strong><?= htmlspecialchars($carrier['company_name'] ?: $carrier['name']) ?></strong></p>
            <p>📧 <?= htmlspecialchars($carrier['email']) ?></p>
            <p>📞 <?= htmlspecialchars($carrier['phone']) ?></p>
            <p>⭐ <?= $carrier['rating'] ?></p>
        <?php else: ?>
            <p style="color:var(--text-secondary);">Перевозчик ещё не выбран</p>
        <?php endif; ?>
    </div>
</div>

<!-- Отклики -->
<?php if (!empty($bids)): ?>
<div class="card">
    <h3>📋 Отклики (<?= count($bids) ?>)</h3>
    <?php foreach ($bids as $bid): ?>
        <div style="border:1px solid var(--border); padding:12px; margin:8px 0; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <strong><?= htmlspecialchars($bid['company_name'] ?: $bid['name']) ?></strong>
                <br>📧 <?= htmlspecialchars($bid['email']) ?> | 📞 <?= htmlspecialchars($bid['phone']) ?> | ⭐ <?= $bid['rating'] ?>
                <br>Цена: <?= number_format($bid['price'], 0, ',', ' ') ?> ₽
            </div>
            <span class="badge-status <?= $bid['status'] == 'accepted' ? 'badge-carrier' : ($bid['status'] == 'rejected' ? 'badge-admin' : 'badge-owner') ?>"><?= $bid['status'] ?></span>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- История статусов -->
<?php if (!empty($history)): ?>
<div class="card">
    <h3>📅 История статусов</h3>
    <div class="timeline">
        <?php foreach ($history as $h): ?>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div>
                    <strong><?= $h['status'] ?></strong>
                    <br><small><?= date('d.m.Y H:i', strtotime($h['created_at'])) ?> — <?= htmlspecialchars($h['name']) ?> (<?= $h['role'] ?>)</small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Чат -->
<?php if (!empty($messages)): ?>
<div class="card">
    <h3>💬 Чат (<?= count($messages) ?> сообщений)</h3>
    <?php foreach ($messages as $msg): ?>
        <?php $isOwner = ($msg['sender_id'] == $order['user_id']); ?>
        <div class="chat-msg <?= $isOwner ? 'owner' : 'carrier' ?>">
            <strong><?= htmlspecialchars($msg['sender_name']) ?>:</strong>
            <span><?= htmlspecialchars($msg['message']) ?></span>
            <?php if ($msg['file_path']): ?>
                <br><a href="/<?= $msg['file_path'] ?>" target="_blank">📎 Файл</a>
            <?php endif; ?>
            <br><small style="opacity:0.6;"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Документы -->
<?php if (!empty($documents)): ?>
<div class="card">
    <h3>📄 Документы</h3>
    <?php foreach ($documents as $doc): ?>
        <div style="margin:4px 0;">
            <a href="/<?= $doc['file_path'] ?>" target="_blank">📎 <?= $doc['type'] ?> — <?= date('d.m.Y H:i', strtotime($doc['created_at'])) ?></a>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Отзывы -->
<?php if (!empty($reviews)): ?>
<div class="card">
    <h3>⭐ Отзывы</h3>
    <?php foreach ($reviews as $r): ?>
        <div style="border-bottom:1px solid var(--border-light); padding:8px 0;">
            <strong><?= htmlspecialchars($r['reviewer_name']) ?></strong> — <?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?>
            <br><?= htmlspecialchars($r['comment']) ?>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>