<?php require_once BASE_PATH . '/templates/header.php']; ?>

<h1>👥 Пользователи</h1>

<div class="admin-tabs">
    <a href="/admin" class="admin-tab">📊 Дашборд</a>
    <a href="/admin/users" class="admin-tab active">👥 Пользователи</a>
    <a href="/admin/orders" class="admin-tab">📦 Заказы</a>
</div>

<div class="card">
    <form class="admin-search" method="GET" action="/admin/users">
        <input type="text" name="search" placeholder="Поиск по имени или email..." value="<?= htmlspecialchars($search) ?>">
        <select name="role">
            <option value="">Все роли</option>
            <option value="owner" <?= $role === 'owner' ? 'selected' : '' ?>>Грузовладельцы</option>
            <option value="carrier" <?= $role === 'carrier' ? 'selected' : '' ?>>Перевозчики</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Найти</button>
    </form>

    <table class="admin-table">
        <tr>
            <th>ID</th><th>Пользователь</th><th>Роль</th><th>Компания</th><th>Дата</th><th>Действия</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td>#<?= $u['id'] ?></td>
            <td>
                <strong><?= htmlspecialchars($u['name']) ?></strong>
                <br><small><?= htmlspecialchars($u['email']) ?></small>
                <?php if ($u['is_admin']): ?><span class="badge-status badge-admin">Админ</span><?php endif; ?>
            </td>
            <td><span class="badge-status <?= $u['role'] === 'owner' ? 'badge-owner' : 'badge-carrier' ?>"><?= $u['role'] === 'owner' ? 'Владелец' : 'Перевозчик' ?></span></td>
            <td><?= htmlspecialchars($u['company_name'] ?: '—') ?></td>
            <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
            <td>
                <a href="/admin/user/<?= $u['id'] ?>" class="btn btn-outline btn-xs">🔍</a>
                <?php if (!$u['is_admin']): ?>
                <form method="POST" action="/admin/delete_user/<?= $u['id'] ?>" style="display:inline;" onsubmit="return confirm('Удалить пользователя?')">
                    <button class="btn btn-danger btn-xs">🗑️</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once BASE_PATH . '/templates/footer.php']; ?>