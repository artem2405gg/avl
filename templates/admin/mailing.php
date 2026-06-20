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
</style>

<h1>📢 Массовая рассылка</h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices">🧾 Счета</a>
    <a href="/admin/tickets">🎧 Тикеты</a>
    <a href="/admin/mailing" class="active">📢 Рассылка</a>
    <a href="/dashboard">← На сайт</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="card" style="max-width: 700px;">
    <form method="POST">
        <div class="form-group">
            <label>Кому отправляем</label>
            <select name="target" required>
                <option value="all">Всем пользователям</option>
                <option value="carriers">Только перевозчикам</option>
                <option value="owners">Только грузовладельцам</option>
            </select>
        </div>
        <div class="form-group">
            <label>Тема письма</label>
            <input type="text" name="subject" required placeholder="Например: Новые заказы на платформе">
        </div>
        <div class="form-group">
            <label>Сообщение</label>
            <textarea name="message" rows="6" required placeholder="Текст рассылки..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">📢 Отправить рассылку</button>
    </form>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>