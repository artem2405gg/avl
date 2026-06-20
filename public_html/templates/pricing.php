<?php require_once BASE_PATH . '/templates/header.php'; ?>

<h2>Тарифы для перевозчиков</h2>

<div class="grid-2" style="margin-top:20px;">
    <!-- Бесплатный -->
    <div class="card" style="text-align:center;">
        <h3>🚀 Бесплатный</h3>
        <div style="font-size:36px; font-weight:bold; margin:15px 0;">0 ₽</div>
        <p>3 отклика в месяц</p>
        <p>1 документ</p>
        <p>Базовая поддержка</p>
    </div>
    
    <!-- Базовый -->
    <div class="card" style="text-align:center; border: 2px solid #007bff;">
        <h3>💼 Базовый</h3>
        <div style="font-size:36px; font-weight:bold; margin:15px 0; color:#007bff;">1 990 ₽/мес</div>
        <p>50 откликов в месяц</p>
        <p>Все документы</p>
        <p>Хранение 1 год</p>
        <p>Приоритетная поддержка</p>
        <button class="btn btn-primary" style="width:100%;" onclick="alert('Для оплаты свяжитесь с менеджером')">Выбрать</button>
    </div>
</div>

<p style="text-align:center; margin-top:20px; color:#666;">Для оплаты напишите нам на info@avllogist.ru</p>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>