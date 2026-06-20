<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .notif-page { max-width: 700px; margin: 0 auto; }
    
    .notif-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        transition: all 0.2s;
        cursor: pointer;
    }
    .notif-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .notif-card.unread { border-left: 4px solid var(--accent); }
    
    .notif-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
    }
    
    .notif-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .notif-icon.bid { background: #eef2ff; }
    .notif-icon.order { background: #fef3c7; }
    .notif-icon.chat { background: #ecfdf5; }
    .notif-icon.support { background: #fce7f3; }
    .notif-icon.mailing { background: #fff7ed; }
    .notif-icon.ref { background: #f0fdf4; }
    .notif-icon.system { background: #f3f4f6; }
    
    .notif-body { flex: 1; min-width: 0; }
    .notif-title { font-weight: 700; font-size: 14px; line-height: 1.3; }
    .notif-meta { font-size: 11px; color: var(--text-secondary); margin-top: 3px; display: flex; align-items: center; gap: 8px; }
    .notif-badge {
        font-size: 9px; padding: 2px 8px; border-radius: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .notif-badge.bid { background: #eef2ff; color: #4361ee; }
    .notif-badge.order { background: #fef3c7; color: #92400e; }
    .notif-badge.chat { background: #ecfdf5; color: #059669; }
    .notif-badge.support { background: #fce7f3; color: #be185d; }
    .notif-badge.mailing { background: #fff7ed; color: #ea580c; }
    .notif-badge.ref { background: #f0fdf4; color: #166534; }
    
    .notif-content {
        display: none;
        padding: 0 18px 16px 18px;
        font-size: 13px;
        color: var(--text-secondary);
        line-height: 1.7;
    }
    .notif-content.show { display: block; }
    .notif-content .text { 
        background: #f9fafb; 
        padding: 14px; 
        border-radius: 10px; 
        white-space: pre-wrap;
        margin-top: 4px;
    }
    
    .empty-notif {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-notif .icon { font-size: 56px; margin-bottom: 14px; }
    .empty-notif h3 { margin-bottom: 6px; }
    .empty-notif p { font-size: 13px; color: var(--text-secondary); }

    @media (max-width: 768px) {
        .notif-header { padding: 14px; gap: 10px; }
        .notif-icon { width: 38px; height: 38px; font-size: 18px; border-radius: 10px; }
        .notif-title { font-size: 13px; }
    }
</style>

<div class="notif-page">
    <h1>🔔 Уведомления</h1>
    <p class="text-muted" style="margin-bottom: 20px;">Все оповещения о заказах, сообщениях и событиях</p>

    <?php if (empty($notifications)): ?>
        <div class="card empty-notif">
            <div class="icon">🔔</div>
            <h3>Пока тишина</h3>
            <p>Когда появятся новые отклики, сообщения или ответы — они будут здесь</p>
        </div>
    <?php else: ?>
        <?php 
        $icons = [
            'bid' => ['icon' => '📋', 'class' => 'bid', 'label' => 'Отклик'],
            'bid_accepted' => ['icon' => '🤝', 'class' => 'bid', 'label' => 'Выбран'],
            'order' => ['icon' => '📦', 'class' => 'order', 'label' => 'Заказ'],
            'message' => ['icon' => '💬', 'class' => 'chat', 'label' => 'Чат'],
            'support' => ['icon' => '🎧', 'class' => 'support', 'label' => 'Поддержка'],
            'mailing' => ['icon' => '📢', 'class' => 'mailing', 'label' => 'Рассылка'],
            'ref' => ['icon' => '🤝', 'class' => 'ref', 'label' => 'Реферал'],
            'subscription' => ['icon' => '💎', 'class' => 'system', 'label' => 'Подписка'],
            'payment' => ['icon' => '💰', 'class' => 'order', 'label' => 'Оплата'],
            'invoice' => ['icon' => '🧾', 'class' => 'system', 'label' => 'Счёт'],
        ];
        ?>
        <?php foreach ($notifications as $n): ?>
            <?php $info = $icons[$n['type']] ?? ['icon' => '🔔', 'class' => 'system', 'label' => '']; ?>
            <div class="notif-card <?= !$n['is_read'] ? 'unread' : '' ?>" onclick="this.querySelector('.notif-content').classList.toggle('show')">
                <div class="notif-header">
                    <div class="notif-icon <?= $info['class'] ?>"><?= $info['icon'] ?></div>
                    <div class="notif-body">
                        <div class="notif-title"><?= htmlspecialchars($n['title']) ?></div>
                        <div class="notif-meta">
                            <span><?= date('d.m.Y H:i', strtotime($n['created_at'])) ?></span>
                            <?php if ($info['label']): ?>
                                <span class="notif-badge <?= $info['class'] ?>"><?= $info['label'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="notif-content">
                    <div class="text"><?= htmlspecialchars($n['message']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>