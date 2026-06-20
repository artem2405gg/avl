<?php require_once BASE_PATH . '/templates/header.php'; ?>

<div class="card" style="max-width: 400px; margin: 50px auto;">
    <h2 style="text-align:center; margin-bottom:20px;">Вход в систему</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/login">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Войти</button>
        <p style="text-align:center; margin-top:12px;">
    <a href="/forgot" style="font-size:13px; color:var(--text-secondary);">Забыли пароль?</a>
</p>
    </form>
    <p style="text-align:center; margin-top:15px;">Нет аккаунта? <a href="/register">Регистрация</a></p>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>