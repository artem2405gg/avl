<?php require_once BASE_PATH . '/templates/header.php']; ?>

<a href="/admin/users" style="color:#4361ee;">← Назад к пользователям</a>

<div class="card" style="margin-top:16px;">
    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <table class="info-table">
        <tr><td>Email</td><td><?= htmlspecialchars($user['email']) ?></td></tr>
        <tr><td>Телефон</td><td><?= htmlspecialchars($user['phone']) ?></td></tr>
        <tr><td>Роль</td><td><span class="badge-status <?= $user['role'] === 'owner' ? 'badge-owner' : 'badge-carrier' ?>"><?= $user['role'] === 'owner' ? 'Грузовладелец' : 'Перевозчик' ?></span></td></tr>
        <tr><td>Компания</td><td><?= htmlspecialchars($user['company_name'] ?: '—') ?></td></tr>
        <tr><td>ИНН</td><td><?= htmlspecialchars($user['inn'] ?: '—') ?></td></tr>
        <tr><td>Рейтинг</td><td><?= $user['rating'] ?></td></tr>
        <tr><td>Админ</td><td><?= $user['is_admin'] ? '✅ Да' : '❌ Нет' ?></td></tr>
        <tr><td>Дата регистрации</td><td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td></tr>
    </table>
</div>

<h3>Сделки пользователя</h3>
<?php if (empty($orders)): ?>
    <div class="card"><p>Сделок пока нет</p></div>
<?php else: ?>
    <?php foreach ($orders as $o): ?>
    <div class="card">
        <strong><?= htmlspecialchars($o['title']) ?></strong>
        <br>Статус: <?= $o['status'] ?> | <?= number_format($o['price'], 0, ',', ' ') ?> ₽
        <br><a href="/admin/order/<?= $o['id'] ?>">Посмотреть заказ →</a>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php']; ?>