<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .profile-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary), #7c3aed);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        font-weight: 900;
        flex-shrink: 0;
    }
    .profile-name { font-size: 24px; font-weight: 800; }
    .profile-role {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 4px;
    }
    .role-owner { background: #eef2ff; color: #4361ee; }
    .role-carrier { background: #ecfdf5; color: #059669; }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 20px;
        text-align: center;
    }
    .stat-card .stat-value { font-size: 32px; font-weight: 900; font-family: var(--font-mono); }
    .stat-card .stat-label { font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-top: 4px; }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<h1>👤 Личный кабинет</h1>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">✅ Профиль обновлён!</div>
<?php endif; ?>

<div class="profile-header">
    <div class="profile-avatar"><?= mb_substr($profile['name'], 0, 1) ?></div>
    <div>
        <div class="profile-name"><?= htmlspecialchars($profile['name']) ?></div>
        <span class="profile-role <?= $_SESSION['user_role'] === 'owner' ? 'role-owner' : 'role-carrier' ?>">
            <?= $_SESSION['user_role'] === 'owner' ? 'Грузовладелец' : 'Перевозчик' ?>
        </span>
        <div style="margin-top:8px;">
            <a href="/profile/edit" class="btn btn-outline btn-sm">✏️ Редактировать</a>
        </div>
    </div>
</div>

<div class="card">
    <h3>📋 Информация</h3>
    <table class="info-table">
        <tr><td>Email</td><td>
    <?= htmlspecialchars($profile['email']) ?>
    <?php if ($profile['email_verified']): ?>
        <span style="color:#10b981; font-size:16px;" title="Email подтверждён">✅</span>
    <?php else: ?>
        <span style="color:#ef4444; font-size:12px;" title="Email не подтверждён">⚠️ Не подтверждён</span>
    <?php endif; ?>
</td></tr>
        <tr><td>Телефон</td><td><?= htmlspecialchars($profile['phone'] ?: '—') ?></td></tr>
        <tr><td>Компания</td><td><?= htmlspecialchars($profile['company_name'] ?: '—') ?></td></tr>
        <tr><td>ИНН</td><td><?= htmlspecialchars($profile['inn'] ?: '—') ?></td></tr>
        <tr><td>Рейтинг</td><td>⭐ <?= $rating ?> / 5</td></tr>
        <tr><td>На платформе с</td><td><?= date('d.m.Y', strtotime($profile['created_at'])) ?></td></tr>
    </table>
</div>

<div class="stats-grid">
    <?php if ($_SESSION['user_role'] === 'owner'): ?>
        <div class="stat-card">
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Всего заказов</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $completedOrders ?></div>
            <div class="stat-label">Завершено</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($totalSpent, 0, ',', ' ') ?> ₽</div>
            <div class="stat-label">Потрачено</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">⭐ <?= $rating ?></div>
            <div class="stat-label">Рейтинг</div>
        </div>
    <?php else: ?>
        <div class="stat-card">
            <div class="stat-value"><?= $totalDeals ?></div>
            <div class="stat-label">Сделок</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $completedDeals ?></div>
            <div class="stat-label">Выполнено</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($totalEarned, 0, ',', ' ') ?> ₽</div>
            <div class="stat-label">Заработано</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">⭐ <?= $rating ?></div>
            <div class="stat-label">Рейтинг</div>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>