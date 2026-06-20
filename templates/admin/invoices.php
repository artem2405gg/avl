<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .admin-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .admin-tabs a {
        padding: 8px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        color: #6b7280;
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    .admin-tabs a:hover { background: #f3f4f6; }
    .admin-tabs a.active { background: #000; color: white; border-color: #000; }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    .admin-table th {
        background: #f9fafb;
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .admin-table td {
        padding: 12px 16px;
        border-top: 1px solid #f0f0f0;
        font-size: 14px;
    }
    .admin-table tr:hover { background: #f9fafb; }
    .btn-xs { padding: 6px 12px; font-size: 11px; border-radius: 6px; }
</style>

<h1>🧾 Счета</h1>

<div class="admin-tabs">
    <a href="/admin">📊 Дашборд</a>
    <a href="/admin/users">👥 Пользователи</a>
    <a href="/admin/orders">📦 Заказы</a>
    <a href="/admin/invoices" class="active">🧾 Счета</a>
</div>

<?php if (isset($_GET['paid'])): ?>
    <div class="alert alert-success">✅ Счёт оплачен, подписка активирована!</div>
<?php endif; ?>

<?php if (isset($_GET['cancelled'])): ?>
    <div class="alert alert-error">❌ Счёт отменён.</div>
<?php endif; ?>

<div class="card">
    <?php if (empty($invoices)): ?>
        <p style="text-align:center; padding:30px; color:#9ca3af;">Счетов пока нет</p>
    <?php else: ?>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Тариф</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($invoices as $inv): ?>
            <tr>
                <td>#<?= $inv['id'] ?></td>
                <td>
                    <strong><?= htmlspecialchars($inv['name']) ?></strong>
                    <br><small style="color:#6b7280;"><?= htmlspecialchars($inv['email']) ?></small>
                </td>
                <td><strong><?= $inv['plan'] ?></strong></td>
                <td style="font-weight:700;"><?= number_format($inv['amount'], 0, ',', ' ') ?> ₽</td>
                <td>
                    <?php if ($inv['status'] == 'pending'): ?>
                        <span class="badge badge-warning">Ожидает</span>
                    <?php elseif ($inv['status'] == 'paid'): ?>
                        <span class="badge badge-success">Оплачен</span>
                    <?php else: ?>
                        <span style="color:#9ca3af;">Отменён</span>
                    <?php endif; ?>
                </td>
                <td style="font-size:13px;"><?= date('d.m.Y H:i', strtotime($inv['created_at'])) ?></td>
                <td>
                    <?php if ($inv['status'] == 'pending'): ?>
                        <a href="/admin/pay_invoice/<?= $inv['id'] ?>" class="btn btn-success btn-xs" onclick="return confirm('Подтвердить оплату и активировать подписку?')">✅ Оплатить</a>
                        <a href="/admin/cancel_invoice/<?= $inv['id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('Отменить счёт?')">❌</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>