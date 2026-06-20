<?php require_once BASE_PATH . '/templates/header.php'; ?>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2>Создать заказ на перевозку</h2>
    
    <form method="POST" action="/orders/create">
        <div class="form-group">
            <label>Название груза</label>
            <input type="text" name="title" required placeholder="Например: Стройматериалы, паллеты">
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Тип груза</label>
                <select name="cargo_type" required>
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
                <input type="number" name="weight" step="0.1" required placeholder="Например: 5">
            </div>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Объём (м³)</label>
                <input type="number" name="volume" step="0.1" placeholder="Например: 20">
            </div>
            <div class="form-group">
                <label>Стоимость (₽)</label>
                <input type="number" name="price" required placeholder="Например: 50000">
            </div>
        </div>
        
        <div class="form-group">
            <label>Адрес погрузки</label>
            <input type="text" name="pickup_address" required placeholder="Город, улица, дом">
        </div>
        
        <div class="form-group">
            <label>Адрес выгрузки</label>
            <input type="text" name="delivery_address" required placeholder="Город, улица, дом">
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
        
        <button type="submit" class="btn btn-success" style="width:100%;">Опубликовать заказ</button>
    </form>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>