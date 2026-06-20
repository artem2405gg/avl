<?php
global $pdo;
require_once BASE_PATH . '/templates/header.php';

$statusLabels = [
    'new' => ['label' => 'Новый', 'icon' => '🆕'],
    'accepted' => ['label' => 'Перевозчик назначен', 'icon' => '🤝'],
    'pickup' => ['label' => 'Выезд на погрузку', 'icon' => '🚛'],
    'loaded' => ['label' => 'Груз погружен', 'icon' => '📦'],
    'in_transit' => ['label' => 'В пути', 'icon' => '🛣️'],
    'arrived' => ['label' => 'Прибыл на выгрузку', 'icon' => '📍'],
    'unloaded' => ['label' => 'Груз выгружен', 'icon' => '✅'],
    'completed' => ['label' => 'Сделка завершена', 'icon' => '🎉'],
    'cancelled' => ['label' => 'Отменён', 'icon' => '❌'],
];

$currentStatus = $statusLabels[$order['status']] ?? $statusLabels['new'];

$isOwner = ($order['user_id'] == $_SESSION['user_id']);
$isCarrier = false;
$acceptedBid = null;

$stmt = $pdo->prepare("SELECT * FROM bids WHERE order_id = ? AND status = 'accepted'");
$stmt->execute([$order['id']]);
$acceptedBid = $stmt->fetch();

if ($acceptedBid && $acceptedBid['carrier_id'] == $_SESSION['user_id']) {
    $isCarrier = true;
}

$carrierInfo = null;
if ($acceptedBid) {
    $stmt = $pdo->prepare("SELECT name, company_name, phone, rating FROM users WHERE id = ?");
    $stmt->execute([$acceptedBid['carrier_id']]);
    $carrierInfo = $stmt->fetch();
}

// Загруженные сканы
$stmt = $pdo->prepare("SELECT * FROM documents WHERE order_id = ? AND type = 'scan' ORDER BY created_at DESC");
$stmt->execute([$order['id']]);
$scans = $stmt->fetchAll();

$statusOrder = ['new', 'accepted', 'pickup', 'loaded', 'in_transit', 'arrived', 'unloaded', 'completed'];
$currentStep = array_search($order['status'], $statusOrder);
if ($currentStep === false) $currentStep = 0;
$totalSteps = count($statusOrder) - 1;
$progress = $order['status'] === 'cancelled' ? 0 : round(($currentStep / $totalSteps) * 100);
?>

<style>
    .status-actions { display: flex; gap: 10px; flex-wrap: wrap; margin: 20px 0; }
    .status-actions form { display: inline-block; }
    .progress-bar { background: #e5e7eb; border-radius: 10px; height: 8px; margin: 20px 0 10px; overflow: hidden; }
    .progress-fill { background: linear-gradient(90deg, #4361ee, #2ec4b6); height: 100%; border-radius: 10px; transition: width 0.5s; }
    .status-steps { display: flex; justify-content: space-between; font-size: 11px; color: #9ca3af; gap: 4px; }
    .status-step { text-align: center; flex: 1; min-width: 55px; }
    .status-step.done { color: #4361ee; font-weight: 600; }
    .status-step.current { color: #2ec4b6; font-weight: 700; font-size: 12px; }
    .status-step .dot { width: 8px; height: 8px; border-radius: 50%; background: #e5e7eb; margin: 4px auto; }
    .status-step.done .dot { background: #4361ee; }
    .status-step.current .dot { background: #2ec4b6; width: 12px; height: 12px; box-shadow: 0 0 0 4px rgba(46,196,182,0.2); }
    .timeline { position: relative; padding-left: 30px; }
    .timeline::before { content: ''; position: absolute; left: 12px; top: 0; bottom: 0; width: 2px; background: #e5e7eb; }
    .timeline-item { position: relative; margin-bottom: 14px; }
    .timeline-dot { position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #4361ee; }
    .timeline-item.past .timeline-dot { background: #d1d5db; }
    .timeline-item.current .timeline-dot { background: #2ec4b6; width: 14px; height: 14px; left: -26px; top: 2px; }
    .timeline-time { font-size: 11px; color: #9ca3af; }
    .scan-preview { display: inline-block; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin: 4px; text-decoration: none; color: inherit; }
    .scan-preview:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .scan-preview img { width: 150px; height: 150px; object-fit: cover; display: block; }
    .scan-preview .scan-icon { width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; background: #fee2e2; font-size: 48px; }
    .scan-preview .scan-name { font-size: 10px; padding: 6px 8px; text-align: center; background: #f9fafb; }
    @media (max-width: 768px) { .status-steps { font-size: 9px; } .status-step { min-width: 40px; } }
</style>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:10px;">
        <div>
            <h2><?= htmlspecialchars($order['title']) ?></h2>
            <span class="badge"><?= $currentStatus['icon'] ?> <?= $currentStatus['label'] ?></span>
        </div>
        <?php if ($order['status'] != 'new' && $order['status'] != 'cancelled'): ?>
            <a href="/chat/view/<?= $order['id'] ?>" class="btn btn-outline btn-sm">💬 Чат</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['scan_uploaded'])): ?>
        <div class="alert alert-success">✅ Скан успешно загружен!</div>
    <?php endif; ?>

    <?php if ($order['status'] != 'cancelled' && $order['status'] != 'new'): ?>
    <div class="progress-bar"><div class="progress-fill" style="width: <?= $progress ?>%;"></div></div>
    <div class="status-steps">
        <?php 
        $stepNames = ['new' => 'Новый', 'accepted' => 'Назначен', 'pickup' => 'Погрузка', 'loaded' => 'Загружен', 'in_transit' => 'В пути', 'arrived' => 'Прибыл', 'unloaded' => 'Выгружен', 'completed' => 'Завершён'];
        foreach ($statusOrder as $key): 
            $si = array_search($key, $statusOrder);
            $cls = '';
            if ($si < $currentStep) $cls = 'done';
            elseif ($si == $currentStep) $cls = 'current';
        ?>
            <div class="status-step <?= $cls ?>"><div class="dot"></div><?= $stepNames[$key] ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <table class="info-table" style="margin-top:20px;">
        <tr><td>Заказчик:</td><td><?= htmlspecialchars($order['company_name'] ?: $order['name']) ?> | 📞 <?= htmlspecialchars($order['phone']) ?></td></tr>
        <?php if ($carrierInfo): ?>
        <tr><td>Перевозчик:</td><td><?= htmlspecialchars($carrierInfo['company_name'] ?: $carrierInfo['name']) ?> | ⭐ <?= $carrierInfo['rating'] ?> | 📞 <?= htmlspecialchars($carrierInfo['phone']) ?></td></tr>
        <tr><td>Цена перевозки:</td><td style="font-size:18px; font-weight:700; color:#2ec4b6;"><?= number_format($acceptedBid['price'], 0, ',', ' ') ?> ₽</td></tr>
        <?php endif; ?>
        <tr><td>Груз:</td><td><?= htmlspecialchars($order['cargo_type']) ?>, <?= $order['weight'] ?> т, <?= $order['volume'] ?> м³</td></tr>
        <tr><td>Маршрут:</td><td>📍 <?= htmlspecialchars($order['pickup_address']) ?> → 🏁 <?= htmlspecialchars($order['delivery_address']) ?></td></tr>
        <tr><td>Даты:</td><td>📅 Погрузка: <?= $order['pickup_date'] ?> | Доставка: <?= $order['delivery_date'] ?></td></tr>
    </table>

    <!-- МАРШРУТ -->
    <div style="margin-top:16px; padding:20px; background:#f9fafb; border-radius:8px; border:1px solid var(--border); text-align:center;">
        <div style="font-size:40px; margin-bottom:8px;">🗺</div>
        <p style="font-weight:700;">📍 <?= htmlspecialchars($order['pickup_address']) ?></p>
        <p style="font-size:24px; margin:8px 0;">↓</p>
        <p style="font-weight:700; margin-bottom:16px;">🏁 <?= htmlspecialchars($order['delivery_address']) ?></p>
        <a href="https://yandex.ru/maps/?rtext=<?= urlencode($order['pickup_address']) ?>~<?= urlencode($order['delivery_address']) ?>&rtt=auto" target="_blank" class="btn btn-primary">🚛 Открыть в Яндекс.Картах</a>
    </div>

    <!-- КНОПКИ СТАТУСА -->
    <div class="status-actions">
        <?php if ($isCarrier): ?>
            <?php if ($order['status'] == 'accepted'): ?>
                <form method="POST" action="/status/update/<?= $order['id'] ?>">
                    <input type="hidden" name="status" value="pickup"><button type="submit" class="btn btn-primary btn-lg">🚛 Выехал на погрузку</button>
                </form>
            <?php elseif ($order['status'] == 'pickup'): ?>
                <form method="POST" action="/status/update/<?= $order['id'] ?>">
                    <input type="hidden" name="status" value="loaded"><button type="submit" class="btn btn-primary btn-lg">📦 Груз погружен</button>
                </form>
            <?php elseif ($order['status'] == 'loaded'): ?>
                <form method="POST" action="/status/update/<?= $order['id'] ?>">
                    <input type="hidden" name="status" value="in_transit"><button type="submit" class="btn btn-primary btn-lg">🛣️ В пути</button>
                </form>
            <?php elseif ($order['status'] == 'in_transit'): ?>
                <form method="POST" action="/status/update/<?= $order['id'] ?>">
                    <input type="hidden" name="status" value="arrived"><button type="submit" class="btn btn-success btn-lg">📍 Прибыл на выгрузку</button>
                </form>
            <?php elseif ($order['status'] == 'arrived'): ?>
                <form method="POST" action="/status/update/<?= $order['id'] ?>">
                    <input type="hidden" name="status" value="unloaded"><button type="submit" class="btn btn-success btn-lg">✅ Груз выгружен</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($isOwner && $order['status'] == 'unloaded' && $order['payment_status'] == 'confirmed'): ?>
            <form method="POST" action="/status/update/<?= $order['id'] ?>">
                <input type="hidden" name="status" value="completed"><button type="submit" class="btn btn-success btn-lg">🎉 Завершить сделку</button>
            </form>
        <?php elseif ($isOwner && $order['status'] == 'unloaded' && $order['payment_status'] != 'confirmed'): ?>
            <p style="color:#92400e; margin-top:8px;">⏳ Дождитесь подтверждения оплаты перевозчиком.</p>
        <?php endif; ?>

        <?php if (($isOwner || $isCarrier) && in_array($order['status'], ['new', 'accepted', 'pickup'])): ?>
            <form method="POST" action="/status/update/<?= $order['id'] ?>" onsubmit="return confirm('Отменить заказ?')">
                <input type="hidden" name="status" value="cancelled"><button type="submit" class="btn btn-danger">❌ Отменить</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- ОПЛАТА -->
    <?php if (($order['status'] == 'unloaded' || $order['status'] == 'completed') && $acceptedBid): ?>
        <?php
        $stmt = $pdo->prepare("SELECT bank_details FROM users WHERE id = ?");
        $stmt->execute([$acceptedBid['carrier_id']]);
        $carrierBank = $stmt->fetch();
        ?>
        <div style="margin-top:20px; padding:20px; background:#fffbeb; border-radius:10px; border:1px solid #f59e0b;">
            <h3 style="margin-bottom:12px;">💰 Оплата заказа</h3>
            
            <?php if (!$order['payment_status'] && $isCarrier && !empty($carrierBank['bank_details'])): ?>
                <form method="POST" action="/payment/request/<?= $order['id'] ?>">
                    <button type="submit" class="btn btn-accent">📋 Выставить счёт заказчику</button>
                </form>
            <?php elseif (!$order['payment_status'] && $isCarrier && empty($carrierBank['bank_details'])): ?>
                <p style="color:#ef4444;">⚠️ Укажите реквизиты в <a href="/profile/edit">профиле</a></p>
            <?php elseif ($order['payment_status'] == 'pending'): ?>
                <p><strong>📋 Статус:</strong> Счёт выставлен, ожидает оплаты.</p>
                <?php if ($isOwner && !empty($carrierBank['bank_details'])): ?>
                    <div style="background:white; padding:16px; border-radius:8px; margin:12px 0; font-family:var(--font-mono); font-size:13px; line-height:1.8; white-space:pre-line;">
                        <?= htmlspecialchars($carrierBank['bank_details']) ?>
                    </div>
                    <button class="btn btn-sm btn-outline" onclick="copyBankDetails()">📋 Скопировать реквизиты</button>
                <?php endif; ?>
                <?php if ($isCarrier): ?>
                    <form method="POST" action="/payment/confirm/<?= $order['id'] ?>" style="margin-top:8px;">
                        <button type="submit" class="btn btn-success btn-sm">✅ Отметить оплату (деньги пришли)</button>
                    </form>
                <?php endif; ?>
            <?php elseif ($order['payment_status'] == 'paid'): ?>
                <p><strong>⏳ Статус:</strong> Ожидает подтверждения.</p>
                <?php if ($isCarrier): ?>
                    <form method="POST" action="/payment/confirm/<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-success">✅ Подтвердить оплату окончательно</button>
                    </form>
                <?php endif; ?>
            <?php elseif ($order['payment_status'] == 'confirmed'): ?>
                <p style="color:#10b981;"><strong>✅ Статус:</strong> Оплата подтверждена перевозчиком!</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- ТАЙМЕР ДЛЯ ЗАКАЗЧИКА -->
    <?php if ($isOwner && $order['status'] == 'unloaded'): ?>
        <?php
        $stmt = $pdo->prepare("SELECT created_at FROM order_status_history WHERE order_id = ? AND status = 'unloaded' ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$order['id']]);
        $unloadedTime = $stmt->fetch();
        $deadline = strtotime($unloadedTime['created_at']) + 86400;
        $remaining = $deadline - time();
        ?>
        <?php if ($remaining > 0): ?>
            <div style="background:#fff3cd; border:1px solid #f59e0b; padding:16px; border-radius:8px; margin-top:16px; text-align:center;">
                <p style="font-weight:700; color:#92400e; margin-bottom:8px;">⏰ Завершите сделку!</p>
                <p style="font-size:14px; color:#92400e;">Осталось: <strong id="countdown"></strong></p>
            </div>
            <script>
            var deadline = <?= $remaining ?>;
            function updateCountdown() {
                var h = Math.floor(deadline / 3600);
                var m = Math.floor((deadline % 3600) / 60);
                var s = Math.floor(deadline % 60);
                var el = document.getElementById('countdown');
                if (el) {
                    if (deadline <= 0) { el.textContent = 'Автозавершение...'; location.reload(); }
                    else { el.textContent = h + ' ч ' + m + ' мин ' + s + ' сек'; }
                }
                deadline--;
            }
            if (document.getElementById('countdown')) { setInterval(updateCountdown, 1000); updateCountdown(); }
            </script>
        <?php endif; ?>
    <?php endif; ?>

    <!-- ДОКУМЕНТЫ И СКАНЫ -->
    <?php if ($order['status'] != 'new' && $order['status'] != 'cancelled'): ?>
    <div style="margin-top:20px; padding:20px; background:#f0fdf4; border-radius:10px;">
        <h3 style="margin-bottom:16px;">📄 Документы по сделке</h3>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:16px;">
            <a href="/documents/view/<?= $order['id'] ?>?type=contract" class="btn btn-sm btn-outline" target="_blank">📋 Договор-заявка</a>
            <a href="/documents/view/<?= $order['id'] ?>?type=waybill" class="btn btn-sm btn-outline" target="_blank">🚛 Транспортная накладная</a>
            <?php if ($order['status'] == 'completed'): ?>
                <a href="/documents/view/<?= $order['id'] ?>?type=act" class="btn btn-sm btn-outline" target="_blank">✅ Акт выполненных работ</a>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($scans)): ?>
            <div style="border-top:1px solid #c3e6cb; padding-top:16px; margin-top:8px;">
                <p style="font-weight:700; font-size:13px; margin-bottom:10px;">📤 Загруженные сканы ТН:</p>
                <div style="display:flex; flex-wrap:wrap; gap:10px;">
                    <?php foreach ($scans as $scan): ?>
                        <a href="/<?= htmlspecialchars($scan['file_path']) ?>" target="_blank" class="scan-preview">
                            <?php $ext = strtolower(pathinfo($scan['file_path'], PATHINFO_EXTENSION)); ?>
                            <?php if ($ext === 'pdf'): ?>
                                <div class="scan-icon">📄</div>
                            <?php else: ?>
                                <img src="/<?= htmlspecialchars($scan['file_path']) ?>" alt="Скан ТН" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div class="scan-icon" style="display:none;">🖼️</div>
                            <?php endif; ?>
                            <div class="scan-name"><?= date('d.m.Y H:i', strtotime($scan['created_at'])) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($isCarrier && in_array($order['status'], ['unloaded', 'completed'])): ?>
            <div style="border-top:1px solid #c3e6cb; padding-top:16px; margin-top:16px;">
                <p style="font-weight:700; font-size:13px;">📤 Загрузить скан подписанной ТН:</p>
                <form method="POST" action="/upload/scan/<?= $order['id'] ?>" enctype="multipart/form-data">
                    <input type="file" name="scan" accept=".pdf,.jpg,.jpeg,.png" style="margin:8px 0;">
                    <button type="submit" class="btn btn-sm btn-accent">Загрузить скан</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Таймлайн -->
<?php if ($order['status'] != 'new' && $order['status'] != 'cancelled'): ?>
<div class="card">
    <h3>📋 История заказа</h3>
    <?php
    $stmt = $pdo->prepare("SELECT h.*, u.name, u.role FROM order_status_history h JOIN users u ON h.changed_by = u.id WHERE h.order_id = ? ORDER BY h.created_at ASC");
    $stmt->execute([$order['id']]);
    $historyItems = $stmt->fetchAll();
    ?>
    <?php if (empty($historyItems)): ?>
        <p style="color:#9ca3af;">История пока пуста</p>
    <?php else: ?>
        <div class="timeline">
            <?php 
            $prevTime = null;
            foreach ($historyItems as $item): 
                $info = $statusLabels[$item['status']] ?? ['label' => $item['status'], 'icon' => '📍'];
                $time = strtotime($item['created_at']);
                $duration = '';
                if ($prevTime) {
                    $diff = $time - $prevTime;
                    if ($diff < 60) $duration = 'через ' . $diff . ' сек';
                    elseif ($diff < 3600) $duration = 'через ' . round($diff / 60) . ' мин';
                    elseif ($diff < 86400) $duration = 'через ' . round($diff / 3600, 1) . ' ч';
                    else $duration = 'через ' . round($diff / 86400, 1) . ' дн';
                }
                $prevTime = $time;
            ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div>
                        <strong><?= $info['icon'] ?> <?= $info['label'] ?></strong>
                        <div style="font-size:12px; color:#6b7280;"><?= date('d.m.Y H:i', $time) ?><?= $duration ? '<span style="color:#9ca3af;"> · '.$duration.'</span>' : '' ?></div>
                        <div style="font-size:11px; color:#9ca3af;"><?= htmlspecialchars($item['name']) ?> (<?= $item['role'] == 'owner' ? 'Заказчик' : 'Перевозчик' ?>)</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Отзывы -->
<?php if ($order['status'] == 'completed'): ?>
    <?php
    $stmt = $pdo->prepare("SELECT id FROM reviews WHERE order_id = ? AND reviewer_id = ?");
    $stmt->execute([$order['id'], $_SESSION['user_id']]);
    $hasReview = $stmt->fetch();
    ?>
    <div class="card" style="text-align: center;">
        <?php if (!$hasReview): ?>
            <h3>⭐ Оцените сделку</h3>
            <p style="color:var(--text-secondary);">Оставьте отзыв о партнёре.</p>
            <a href="/review/create/<?= $order['id'] ?>" class="btn btn-primary">Оставить отзыв</a>
        <?php else: ?>
            <h3>✅ Спасибо за отзыв!</h3>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Отклики -->
<?php if ($isOwner && !empty($bids) && $order['status'] == 'new'): ?>
<div class="card">
    <h3>Отклики перевозчиков (<?= count($bids) ?>)</h3>
    <?php foreach ($bids as $bid): ?>
        <div style="border:1px solid #e5e7eb; padding:14px; margin:8px 0; border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <strong><?= htmlspecialchars($bid['company_name']) ?></strong> | ⭐ <?= $bid['rating'] ?>
                <br>Цена: <span style="font-size:18px; font-weight:700; color:#2ec4b6;"><?= number_format($bid['price'], 0, ',', ' ') ?> ₽</span>
            </div>
            <?php if ($bid['status'] == 'pending'): ?>
                <form method="POST" action="/bids/accept">
                    <input type="hidden" name="bid_id" value="<?= $bid['id'] ?>"><button type="submit" class="btn btn-success">Выбрать</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($_SESSION['user_role'] == 'carrier' && !$myBid && $order['status'] == 'new'): ?>
<div class="card">
    <h3>Предложить свою цену</h3>
    <form method="POST" action="/bids/place/<?= $order['id'] ?>">
        <div class="form-group"><label>Ваша цена (₽)</label><input type="number" name="price" required placeholder="45000"></div>
        <button type="submit" class="btn btn-success">Отправить отклик</button>
    </form>
</div>
<?php endif; ?>

<?php if ($myBid && $order['status'] == 'new'): ?>
    <div class="alert alert-success">✅ Вы откликнулись на <?= number_format($myBid['price'], 0, ',', ' ') ?> ₽. Ожидайте.</div>
<?php endif; ?>

<a href="/orders" class="btn btn-ghost">← К списку заказов</a>

<script>
function copyBankDetails() {
    var text = document.querySelector('.bank-details-text') ? document.querySelector('.bank-details-text').textContent : '';
    if (!text) {
        var el = document.querySelector('[style*="white-space:pre-line"]');
        if (el) text = el.textContent;
    }
    navigator.clipboard.writeText(text).then(function() { alert('Реквизиты скопированы!'); });
}
</script>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>