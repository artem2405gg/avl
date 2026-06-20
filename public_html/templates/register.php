<?php require_once BASE_PATH . '/templates/header.php'; ?>

<div class="card" style="max-width: 500px; margin: 30px auto;">
    <h2 style="text-align:center; margin-bottom:20px;">Регистрация</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/register">
        <div class="form-group">
            <label>Я регистрируюсь как:</label>
            <select name="role" id="role" onchange="toggleCompanyFields()">
                <option value="owner">Грузовладелец (ищу перевозчика)</option>
                <option value="carrier">Перевозчик (ищу заказы)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>ФИО / Имя</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Телефон</label>
            <input type="text" name="phone" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" required minlength="6">
        </div>
        
        <div id="companyFields">
            <div class="form-group">
                <label>Название компании / ИП</label>
                <input type="text" name="company_name">
            </div>
            <div class="form-group">
                <label>ИНН</label>
                <input type="text" name="inn">
            </div>
        </div>
        
        <button type="submit" class="btn btn-success" style="width:100%;">Зарегистрироваться</button>
    </form>
    <p style="text-align:center; margin-top:15px;">Уже есть аккаунт? <a href="/login">Войти</a></p>
</div>

<script>
function toggleCompanyFields() {
    // Оставляем поля видимыми для всех
}
</script>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>