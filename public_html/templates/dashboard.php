<?php require_once BASE_PATH . '/templates/header.php'; ?>

<h1>Добро пожаловать, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
<p style="color:#666; margin-bottom:25px;">
    Вы вошли как: <strong><?= $_SESSION['user_role'] === 'owner' ? 'Грузовладелец' : 'Перевозчик' ?></strong>
</p>

<div class="grid-2">
    <a href="/orders" style="text-decoration:none;">
        <div class="card" style="text-align:center;">
            <h3>📋 Все заказы</h3>
            <p>Просмотр доступных заказов</p>
        </div>
    </a>
    
    <?php if ($_SESSION['user_role'] === 'owner'): ?>
    <a href="/orders/create" style="text-decoration:none;">
        <div class="card" style="text-align:center;">
            <h3>➕ Создать заказ</h3>
            <p>Опубликовать новый груз</p>
        </div>
    </a>
    <?php endif; ?>
    
    <a href="/orders/my" style="text-decoration:none;">
        <div class="card" style="text-align:center;">
            <h3>📦 Мои сделки</h3>
            <p><?= $_SESSION['user_role'] === 'owner' ? 'Мои заказы' : 'Мои отклики' ?></p>
        </div>
    </a>
    
    <?php if ($_SESSION['user_role'] === 'carrier'): ?>
    <a href="/pricing" style="text-decoration:none;">
        <div class="card" style="text-align:center;">
            <h3>💎 Тарифы</h3>
            <p>Подписка и возможности</p>
        </div>
    </a>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>