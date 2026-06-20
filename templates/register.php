<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .reg-tabs { display: flex; gap: 0; margin-bottom: 24px; border-radius: 10px; overflow: hidden; border: 2px solid var(--border); }
    .reg-tab { flex: 1; text-align: center; padding: 14px; cursor: pointer; font-weight: 700; font-size: 14px; background: white; color: var(--text-secondary); transition: all 0.2s; border: none; font-family: 'Inter', sans-serif; }
    .reg-tab.active { background: var(--primary); color: white; }
    .reg-tab:first-child { border-right: 2px solid var(--border); }
    .quick-reg { text-align: center; padding: 30px; }
    .quick-reg .big-icon { font-size: 56px; margin-bottom: 16px; }
    .quick-reg h3 { font-size: 20px; margin-bottom: 8px; }
    .quick-reg p { color: var(--text-secondary); margin-bottom: 20px; }
</style>

<div class="card" style="max-width: 500px; margin: 30px auto;">
    <div class="reg-tabs">
        <button class="reg-tab active" onclick="showRole('carrier', this)">🚛 Я перевозчик</button>
        <button class="reg-tab" onclick="showRole('owner', this)">📦 Я грузовладелец</button>
    </div>
    
    <div id="carrierForm">
        <div class="quick-reg">
            <div class="big-icon">🚛</div>
            <h3>Регистрация для перевозчиков</h3>
            <p>30 дней бесплатного доступа ко всем заказам</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/register">
            <input type="hidden" name="role" value="carrier">
            <input type="hidden" name="ref_code" value="<?= htmlspecialchars($_SESSION['ref_code'] ?? '') ?>">
            
            <div class="form-group"><label>Имя</label><input type="text" name="name" required placeholder="Ваше имя"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required placeholder="email@example.com"></div>
            <div class="form-group"><label>Телефон</label><input type="text" name="phone" required placeholder="+7 (___) ___-__-__"></div>
            <div class="form-group"><label>Пароль</label><input type="password" name="password" required placeholder="Минимум 6 символов"></div>
            <div class="form-group"><label>Компания / ИП (необязательно)</label><input type="text" name="company_name" placeholder="Название компании"></div>
            <button type="submit" class="btn btn-accent btn-block">🚛 Зарегистрироваться и получить заказы</button>
        </form>
    </div>
    
    <div id="ownerForm" style="display:none;">
        <div class="quick-reg">
            <div class="big-icon">📦</div>
            <h3>Регистрация для грузовладельцев</h3>
            <p>Бесплатное размещение заказов навсегда</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/register">
            <input type="hidden" name="role" value="owner">
            <input type="hidden" name="ref_code" value="<?= htmlspecialchars($_SESSION['ref_code'] ?? '') ?>">
            
            <div class="form-group"><label>Имя</label><input type="text" name="name" required placeholder="Ваше имя"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required placeholder="email@example.com"></div>
            <div class="form-group"><label>Телефон</label><input type="text" name="phone" required placeholder="+7 (___) ___-__-__"></div>
            <div class="form-group"><label>Пароль</label><input type="password" name="password" required placeholder="Минимум 6 символов"></div>
            <div class="form-group"><label>Компания / ИП (необязательно)</label><input type="text" name="company_name" placeholder="Название компании"></div>
            <button type="submit" class="btn btn-primary btn-block">📦 Зарегистрироваться и создать заказ</button>
        </form>
    </div>
    
    <p style="text-align:center; margin-top:16px;">Уже есть аккаунт? <a href="/login">Войти</a></p>
</div>

<script>
function showRole(role, btn) {
    document.querySelectorAll('.reg-tab').forEach(function(t) { t.classList.remove('active'); });
    btn.classList.add('active');
    document.getElementById('carrierForm').style.display = role === 'carrier' ? 'block' : 'none';
    document.getElementById('ownerForm').style.display = role === 'owner' ? 'block' : 'none';
}
</script>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>