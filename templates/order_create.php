<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .calc-price-box {
        background: #f0fdf4;
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        text-align: center;
        display: none;
    }
    .calc-price-box.show { display: block; }
    .calc-price-box .price {
        font-size: 32px;
        font-weight: 900;
        color: #10b981;
        font-family: 'SF Mono', 'Cascadia Code', monospace;
    }
    .calc-price-box .hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
</style>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2>Создать заказ на перевозку</h2>
    
    <div class="calc-price-box" id="priceHint">
        <div>💡 Рекомендуемая цена</div>
        <div class="price" id="suggestedPrice">0 ₽</div>
        <div class="hint" id="priceDetails">На основе расстояния и типа груза</div>
    </div>
    
    <form method="POST" action="/orders/create">
        <div class="form-group">
            <label>Название груза</label>
            <input type="text" name="title" required placeholder="Например: Стройматериалы, паллеты">
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Тип груза</label>
                <select name="cargo_type" id="cargoType" required onchange="calculatePrice()">
                    <option value="">Выберите...</option>
                    <option value="Сборный груз">Сборный груз</option>
                    <option value="Стройматериалы">Стройматериалы</option>
                    <option value="Продукты питания">Продукты питания</option>
                    <option value="Оборудование">Оборудование</option>
                    <option value="Мебель">Мебель</option>
                    <option value="Другое">Другое</option>
                </select>
            </div>
            <div class="form-group">
                <label>Вес (тонн)</label>
                <input type="number" name="weight" id="weightInput" step="0.1" required placeholder="5" oninput="calculatePrice()">
            </div>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Объём (м³)</label>
                <input type="number" name="volume" id="volumeInput" step="0.1" placeholder="20" oninput="calculatePrice()">
            </div>
            <div class="form-group">
                <label>Стоимость (₽)</label>
                <div style="display:flex; gap:8px;">
    <input type="number" name="price" id="priceInput" required placeholder="50000" style="flex:1;">
    <button type="button" class="btn btn-sm btn-outline" onclick="applySuggestion()" style="white-space:nowrap; display:none;" id="applyBtn">💡 Применить</button>
</div>
            </div>
        </div>
        
        <div class="form-group">
            <label>Адрес погрузки</label>
            <input type="text" name="pickup_address" id="pickupInput" required placeholder="Город, улица, дом" oninput="calculatePrice()">
        </div>
        
        <div class="form-group">
            <label>Адрес выгрузки</label>
            <input type="text" name="delivery_address" id="deliveryInput" required placeholder="Город, улица, дом" oninput="calculatePrice()">
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Дата погрузки</label>
                <input type="date" name="pickup_date" required>
            </div>
            <div class="form-group">
                <label>Дата доставки</label>
                <input type="date" name="delivery_date" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success btn-block">Опубликовать заказ</button>
    </form>
</div>

<script>
// Базовые тарифы за км для разных типов грузов
var cargoRates = {
    'Сборный груз': 35,
    'Стройматериалы': 40,
    'Продукты питания': 45,
    'Оборудование': 50,
    'Мебель': 38,
    'Другое': 35
};

// Примерные расстояния между крупными городами (в км)
var cityDistances = {
    'москва': {'санкт-петербург': 700, 'казань': 800, 'новосибирск': 3300, 'екатеринбург': 1800, 'краснодар': 1350, 'череповец': 500, 'вологда': 450, 'архангельск': 1200},
    'санкт-петербург': {'москва': 700, 'казань': 1500, 'мурманск': 1350, 'псков': 280},
    'казань': {'москва': 800, 'екатеринбург': 900, 'самара': 350},
    'екатеринбург': {'москва': 1800, 'казань': 900, 'челябинск': 200},
    'новосибирск': {'москва': 3300, 'красноярск': 800},
    'краснодар': {'москва': 1350, 'ростов': 250},
};

function extractCity(address) {
    if (!address) return '';
    var parts = address.split(',');
    return parts[0].trim().toLowerCase();
}

function getDistance(cityFrom, cityTo) {
    if (cityFrom === cityTo) return 50;
    
    if (cityDistances[cityFrom] && cityDistances[cityFrom][cityTo]) {
        return cityDistances[cityFrom][cityTo];
    }
    if (cityDistances[cityTo] && cityDistances[cityTo][cityFrom]) {
        return cityDistances[cityTo][cityFrom];
    }
    
    // Если города нет в базе — примерный расчёт
    return 800;
}

function calculatePrice() {
    var cargoType = document.getElementById('cargoType').value;
    var weight = parseFloat(document.getElementById('weightInput').value) || 0;
    var pickup = document.getElementById('pickupInput').value;
    var delivery = document.getElementById('deliveryInput').value;
    
    var cityFrom = extractCity(pickup);
    var cityTo = extractCity(delivery);
    
    if (!cargoType || !pickup || !delivery || cityFrom.length < 2 || cityTo.length < 2) {
        document.getElementById('priceHint').classList.remove('show');
        return;
    }
    
    var distance = getDistance(cityFrom, cityTo);
    var rate = cargoRates[cargoType] || 35;
    var basePrice = distance * rate;
    
    if (weight > 5) {
        basePrice += (weight - 5) * 1000;
    }
    
    var suggested = Math.round(basePrice / 500) * 500;
    
    document.getElementById('priceHint').classList.add('show');
    document.getElementById('suggestedPrice').textContent = suggested.toLocaleString('ru-RU') + ' ₽';
    document.getElementById('priceDetails').textContent = '📏 ~' + distance + ' км · 🏷️ ' + cargoType.toLowerCase();
    document.getElementById('priceHint').classList.add('show');
    document.getElementById('suggestedPrice').textContent = suggested.toLocaleString('ru-RU') + ' ₽';
    document.getElementById('priceDetails').textContent = '📏 ~' + distance + ' км · 🏷️ ' + cargoType.toLowerCase();
    
    // Показываем кнопку "Применить"
    document.getElementById('applyBtn').style.display = 'inline-flex';
    // Сохраняем подсказку
    document.getElementById('applyBtn').setAttribute('data-price', suggested);
}

function applySuggestion() {
    var price = document.getElementById('applyBtn').getAttribute('data-price');
    document.getElementById('priceInput').value = price;
    document.getElementById('priceInput').style.borderColor = '#10b981';
}
    // НЕ меняем поле цены автоматически
    // Только показываем подсказку

</script>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>