<?php require_once BASE_PATH . '/templates/header.php'; ?>

<style>
    .chats-container {
        max-width: 700px;
        margin: 0 auto;
    }
    
    .chat-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        background: var(--surface);
        border: 1px solid var(--border);
        margin-bottom: 10px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
        position: relative;
    }
    
    .chat-item:hover {
        transform: translateX(4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }
    
    .chat-item:active {
        transform: scale(0.98);
    }
    
    .chat-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), #7209b7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 20px;
        flex-shrink: 0;
        position: relative;
    }
    
    .chat-avatar .online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .chat-info {
        flex: 1;
        min-width: 0;
    }
    
    .chat-name {
        font-weight: 700;
        font-size: 15px;
        color: var(--text);
        margin-bottom: 4px;
    }
    
    .chat-order {
        font-size: 13px;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chat-meta {
        text-align: right;
        flex-shrink: 0;
    }
    
    .chat-badge {
        display: inline-block;
        background: #ef4444;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        min-width: 24px;
        text-align: center;
    }
    
    .chat-arrow {
        color: var(--text-secondary);
        font-size: 20px;
        margin-left: 8px;
        transition: all 0.3s;
    }
    
    .chat-item:hover .chat-arrow {
        color: var(--accent);
        transform: translateX(4px);
    }
    
    .empty-chats {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }
    
    .empty-chats .icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.8;
    }
    
    .empty-chats h3 {
        font-size: 18px;
        margin-bottom: 8px;
        color: var(--text);
    }
    
    .empty-chats p {
        font-size: 14px;
    }
    
    @media (max-width: 768px) {
        .chat-item {
            padding: 14px 16px;
            gap: 12px;
        }
        .chat-avatar {
            width: 44px;
            height: 44px;
            font-size: 17px;
        }
        .chat-name {
            font-size: 14px;
        }
    }
</style>

<div class="chats-container">
    <h1>💬 Сообщения</h1>
    <p class="text-muted" style="margin-bottom: 24px;">Переписка по вашим сделкам</p>

    <?php if (empty($chats)): ?>
        <div class="card empty-chats">
            <div class="icon">💬</div>
            <h3>Чатов пока нет</h3>
            <p>Примите сделку, чтобы начать общение с заказчиком или перевозчиком</p>
        </div>
    <?php else: ?>
        <?php foreach ($chats as $chat): 
            $online = false;
            if (!empty($chat['companion_name'])) {
                $stmt = $pdo->prepare("SELECT last_activity FROM users WHERE name = ? OR company_name = ? LIMIT 1");
                $stmt->execute([$chat['companion_name'], $chat['companion_name']]);
                $act = $stmt->fetch();
                $online = $act && $act['last_activity'] && (time() - strtotime($act['last_activity'])) < 300;
            }
        ?>
            <a href="/chat/view/<?= $chat['id'] ?>" class="chat-item">
                <div class="chat-avatar">
                    <?= mb_substr($chat['companion_name'] ?? '?', 0, 1) ?>
                    <div class="online-dot" style="background:<?= $online ? '#10b981' : '#9ca3af' ?>;"></div>
                </div>
                <div class="chat-info">
                    <div class="chat-name"><?= htmlspecialchars($chat['companion_name'] ?? 'Собеседник') ?></div>
                    <div class="chat-order">📦 <?= htmlspecialchars(mb_substr($chat['title'], 0, 40)) ?><?= mb_strlen($chat['title']) > 40 ? '...' : '' ?></div>
                </div>
                <div class="chat-meta">
                    <?php if ($chat['unread'] > 0): ?>
                        <span class="chat-badge"><?= $chat['unread'] ?></span>
                    <?php endif; ?>
                </div>
                <span class="chat-arrow">→</span>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>