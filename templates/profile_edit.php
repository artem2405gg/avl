<?php require_once BASE_PATH . '/templates/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>✏️ Редактировать профиль</h2>
    <?php if (isset($_GET['required']) && $_GET['required'] == 'inn'): ?>
    <div class="alert" style="background:#fff3cd; color:#92400e; border-color:#f59e0b;">
        ⚠️ Для создания заказа необходимо заполнить ИНН.
    </div>
<?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/profile/edit">
        <div class="form-group"><label>Имя *</label><input type="text" name="name" required value="<?= htmlspecialchars($profile['name']) ?>"></div>
        <div class="form-group"><label>Телефон</label><input type="text" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>"></div>
        <div class="form-group"><label>Компания / ИП</label><input type="text" name="company_name" value="<?= htmlspecialchars($profile['company_name']) ?>"></div>
        <div class="form-group"><label>ИНН</label><input type="text" name="inn" value="<?= htmlspecialchars($profile['inn']) ?>"></div>
        
        <div class="form-group">
            <label>Ставка НДС</label>
            <select name="nds_rate">
                <option value="20" <?= ($profile['nds_rate'] ?? '20') == '20' ? 'selected' : '' ?>>НДС 20% (ОСНО)</option>
                <option value="0" <?= ($profile['nds_rate'] ?? '20') == '0' ? 'selected' : '' ?>>Без НДС (УСН)</option>
            </select>
        </div>
        
        <?php if ($_SESSION['user_role'] === 'carrier'): ?>
        <div class="form-group">
            <label>💰 Реквизиты для оплаты заказов</label>
            <textarea name="bank_details" rows="5" placeholder="ИП Иванов И.И.&#10;ИНН: 123456789012&#10;Р/с: 40802810000000000001&#10;Банк: АО «Т-Банк»&#10;БИК: 044525974&#10;К/с: 30101810145250000974"><?= htmlspecialchars($profile['bank_details'] ?? '') ?></textarea>
        </div>
        <?php endif; ?>
        
        <div class="form-group"><label>Новый пароль (не обязательно)</label><input type="password" name="new_password" placeholder="Минимум 6 символов"></div>
        <button type="submit" class="btn btn-primary btn-block">💾 Сохранить</button>
        <a href="/profile" class="btn btn-ghost btn-block" style="margin-top:8px;">Отмена</a>
    </form>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>