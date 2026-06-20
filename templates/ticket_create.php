<?php require_once BASE_PATH . '/templates/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>➕ Новое обращение</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Тема</label>
            <input type="text" name="subject" required placeholder="Кратко опишите проблему">
        </div>
        <div class="form-group">
            <label>Сообщение</label>
            <textarea name="message" rows="6" required placeholder="Опишите ситуацию подробно"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Отправить</button>
        <a href="/support" class="btn btn-ghost btn-block" style="margin-top:8px;">← Назад</a>
    </form>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>