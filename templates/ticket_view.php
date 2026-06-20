<?php require_once BASE_PATH . '/templates/header.php'; ?>

<?php
global $pdo;
// Загружаем переписку
$stmt = $pdo->prepare("SELECT * FROM ticket_messages WHERE ticket_id = ? ORDER BY created_at ASC");
$stmt->execute([$ticket['id']]);
$messages = $stmt->fetchAll();
?>

<a href="/support" style="color:var(--primary);">← К списку обращений</a>

<div class="card" style="margin-top:16px;">
    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:16px;">
        <h2><?= htmlspecialchars($ticket['subject']) ?></h2>
        <span class="badge <?= $ticket['status'] == 'open' ? 'badge-warning' : 'badge-success' ?>">
            <?= $ticket['status'] == 'open' ? 'Открыт' : 'Закрыт' ?>
        </span>
    </div>
    
    <!-- Переписка -->
    <?php if (empty($messages)): ?>
        <div style="background:#f9fafb; padding:16px; border-radius:8px; margin-bottom:16px;">
            <p style="font-size:14px; white-space:pre-wrap;"><?= htmlspecialchars($ticket['message']) ?></p>
            <small style="color:var(--text-secondary);"><?= date('d.m.Y H:i', strtotime($ticket['created_at'])) ?></small>
        </div>
    <?php else: ?>
        <?php foreach ($messages as $msg): ?>
            <div style="background:<?= $msg['is_admin'] ? '#f0fdf4' : '#f9fafb' ?>; padding:16px; border-radius:8px; margin-bottom:10px; border-left:3px solid <?= $msg['is_admin'] ? 'var(--success)' : 'var(--border)' ?>;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <strong><?= $msg['is_admin'] ? '🎧 Техподдержка' : '👤 Вы' ?></strong>
                    <small style="color:var(--text-secondary);"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></small>
                </div>
                <p style="font-size:14px; white-space:pre-wrap; margin:0;"><?= htmlspecialchars($msg['message']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Форма ответа -->
    <?php if ($ticket['status'] == 'open'): ?>
        <form method="POST" action="/support/reply/<?= $ticket['id'] ?>" style="margin-top:16px;">
            <div class="form-group">
                <label>Добавить сообщение</label>
                <textarea name="message" rows="3" required placeholder="Введите сообщение..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">📤 Отправить</button>
            
            <?php if ($ticket['admin_reply']): ?>
                <form method="POST" action="/support/close/<?= $ticket['id'] ?>" style="display:inline; margin-left:8px;">
                    <button type="submit" class="btn btn-success btn-sm">✅ Закрыть тикет</button>
                </form>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>