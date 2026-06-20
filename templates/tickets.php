<?php require_once BASE_PATH . '/templates/header.php'; ?>

<h1>🎧 Техподдержка</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?><?php unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="/support/create" class="btn btn-primary" style="margin-bottom:20px;">➕ Создать обращение</a>

<?php if (empty($tickets)): ?>
    <div class="card" style="text-align:center; padding:40px;">
        <div style="font-size:48px;">🎧</div>
        <h3>Обращений пока нет</h3>
        <p style="color:var(--text-secondary);">Задайте вопрос — мы поможем!</p>
    </div>
<?php else: ?>
    <?php foreach ($tickets as $ticket): ?>
        <a href="/support/view/<?= $ticket['id'] ?>" style="text-decoration:none; color:inherit;">
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:start;">
                    <div>
                        <strong><?= htmlspecialchars($ticket['subject']) ?></strong>
                        <p style="font-size:13px; color:var(--text-secondary); margin-top:4px;">
                            <?= htmlspecialchars(mb_substr($ticket['message'], 0, 100)) ?>...
                        </p>
                    </div>
                    <div style="text-align:right;">
                        <span class="badge <?= $ticket['status'] == 'open' ? 'badge-warning' : 'badge-success' ?>">
                            <?= $ticket['status'] == 'open' ? 'Открыт' : 'Закрыт' ?>
                        </span>
                        <br><small style="color:var(--text-secondary);"><?= date('d.m.Y', strtotime($ticket['created_at'])) ?></small>
                    </div>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>