<?php require_once BASE_PATH . '/templates/header.php'; ?>

<a href="/admin/tickets">← К тикетам</a>

<div class="card" style="margin-top:16px;">
    <h2>#<?= $ticket['id'] ?> <?= htmlspecialchars($ticket['subject']) ?></h2>
    <p><strong>От:</strong> <?= htmlspecialchars($ticket['name']) ?></p>
    
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <div style="background:<?= $msg['is_admin'] ? '#f0fdf4' : '#f9fafb' ?>; padding:14px; border-radius:8px; margin:10px 0; border-left:3px solid <?= $msg['is_admin'] ? 'var(--success)' : 'var(--border)' ?>;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                    <strong><?= $msg['is_admin'] ? '🎧 Техподдержка' : '👤 Пользователь' ?></strong>
                    <small style="color:var(--text-secondary);"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></small>
                </div>
                <p style="font-size:14px; white-space:pre-wrap; margin:0;"><?= htmlspecialchars($msg['message']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="background:#f9fafb; padding:14px; border-radius:8px; margin:10px 0;">
            <p style="white-space:pre-wrap;"><?= htmlspecialchars($ticket['message']) ?></p>
        </div>
    <?php endif; ?>
    
    <?php if ($ticket['status'] == 'open'): ?>
        <form method="POST" style="margin-top:16px;">
            <div class="form-group">
                <label>Ответ</label>
                <textarea name="reply" rows="4" required></textarea>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary">📤 Отправить</button>
                <a href="/admin/close_ticket/<?= $ticket['id'] ?>" class="btn btn-outline">Закрыть тикет</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>