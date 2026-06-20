<?php require_once BASE_PATH . '/templates/header.php'; ?>

<?php
global $pdo;
$stmt = $pdo->prepare("SELECT last_activity FROM users WHERE id = ?");
$stmt->execute([$companion['id']]);
$companionActivity = $stmt->fetch();
$online = $companionActivity && $companionActivity['last_activity'] && (time() - strtotime($companionActivity['last_activity'])) < 300;
?>

<style>
    .chat-wrapper {
        max-width: 750px;
        margin: 0 auto;
        height: calc(100vh - 200px);
        min-height: 500px;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .chat-header {
        background: white;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        border-bottom: 1px solid #f0f0f0;
        flex-shrink: 0;
    }

    .chat-back {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; color: #374151; font-size: 18px;
        flex-shrink: 0;
    }

    .chat-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4361ee, #7209b7);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 17px; flex-shrink: 0;
        position: relative;
    }
    
    .chat-avatar .online-dot {
        position: absolute;
        bottom: 1px; right: 1px;
        width: 13px; height: 13px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .chat-header-info { flex: 1; min-width: 0; }
    .chat-header-name { font-weight: 700; font-size: 16px; color: #1f2937; display: flex; align-items: center; gap: 8px; }
    .chat-header-order { font-size: 12px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .chat-header-btn {
        width: 38px; height: 38px; border-radius: 50%;
        background: #f3f4f6; border: none; cursor: pointer;
        font-size: 16px; display: flex; align-items: center; justify-content: center;
        color: #6b7280; text-decoration: none;
    }

    .chat-messages {
        flex: 1; overflow-y: auto;
        padding: 20px;
        background: #f8fafc;
        background-image: 
            radial-gradient(circle at 25px 25px, #e5e7eb 0.5px, transparent 0.5px),
            radial-gradient(circle at 75px 75px, #e5e7eb 0.5px, transparent 0.5px);
        background-size: 100px 100px;
        display: flex; flex-direction: column; gap: 4px;
    }
    
    .chat-messages {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

    .chat-date-divider { text-align: center; margin: 16px 0; position: relative; }
    .chat-date-divider span {
        background: #e5e7eb; color: #6b7280;
        padding: 4px 14px; border-radius: 12px;
        font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .message-row { display: flex; gap: 8px; max-width: 85%; animation: messageIn 0.3s ease-out; }
    @keyframes messageIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .message-row.mine { align-self: flex-end; flex-direction: row-reverse; }
    .message-row:not(.mine) { align-self: flex-start; }

    .message-avatar {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 12px;
    }
    .message-row.mine .message-avatar { display: none; }

    .message-content { display: flex; flex-direction: column; gap: 2px; }
    .message-bubble {
        padding: 10px 14px; font-size: 14px; line-height: 1.5;
        position: relative; word-wrap: break-word; white-space: pre-wrap;
    }
    .message-row.mine .message-bubble { background: linear-gradient(135deg, #4361ee, #3a56d4); color: white; border-radius: 18px 18px 4px 18px; }
    .message-row:not(.mine) .message-bubble { background: white; border: 1px solid #e5e7eb; border-radius: 18px 18px 18px 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }

    .message-bubble img { max-width: 250px; max-height: 250px; border-radius: 12px; cursor: pointer; display: block; }
    .message-bubble a { color: inherit; text-decoration: none; }

    .message-time { font-size: 10px; margin-top: 2px; display: flex; align-items: center; gap: 4px; }
    .message-row.mine .message-time { color: rgba(255,255,255,0.7); justify-content: flex-end; }
    .message-row:not(.mine) .message-time { color: #9ca3af; }

    .chat-input {
        padding: 14px 16px; border-top: 1px solid #f0f0f0;
        background: white; display: flex; align-items: center; gap: 10px; flex-shrink: 0;
    }
    .chat-input label { cursor: pointer; padding: 10px; font-size: 22px; transition: all 0.2s; }
    .chat-input label:hover { opacity: 0.7; }
    .chat-input input[type="text"] {
        flex: 1; padding: 14px 18px; border: 2px solid #e5e7eb;
        border-radius: 25px; font-size: 14px; font-family: 'Inter', sans-serif;
        transition: all 0.2s; background: #f9fafb;
    }
    .chat-input input:focus { outline: none; border-color: #4361ee; background: white; }
    .chat-input input::placeholder { color: #9ca3af; }

    .chat-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #9ca3af; text-align: center; padding: 20px; }
    .chat-empty-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.8; }

    @media (max-width: 768px) {
        .chat-wrapper { height: calc(100vh - 150px); border-radius: 0; margin: -16px -12px 0; border: none; box-shadow: none; }
        .chat-header { padding: 12px 14px; }
        .chat-messages { padding: 14px; }
        .chat-input { padding: 10px 12px; }
        .message-row { max-width: 90%; }
        .message-bubble img { max-width: 180px; max-height: 180px; }
    }
</style>

<div class="chat-wrapper">
    <div class="chat-header">
        <a href="/messages" class="chat-back">←</a>
        <div class="chat-avatar">
            <?= mb_substr($companion['company_name'] ?: $companion['name'], 0, 1) ?>
            <div class="online-dot" style="background:<?= $online ? '#10b981' : '#9ca3af' ?>;"></div>
        </div>
        <div class="chat-header-info">
            <div class="chat-header-name">
                <?= htmlspecialchars($companion['company_name'] ?: $companion['name']) ?>
                <span style="font-size:11px; color:<?= $online ? '#10b981' : '#9ca3af' ?>;">
                    <?= $online ? '🟢 Онлайн' : '⚫ Был(а) ' . ($companionActivity && $companionActivity['last_activity'] ? date('H:i', strtotime($companionActivity['last_activity'])) : 'давно') ?>
                </span>
            </div>
            <div class="chat-header-order">Заказ: <?= htmlspecialchars(mb_substr($order['title'], 0, 40)) ?><?= mb_strlen($order['title']) > 40 ? '...' : '' ?></div>
        </div>
        <a href="/orders/view/<?= $orderId ?>" class="chat-header-btn" title="К заказу">📋</a>
    </div>

    <div class="chat-messages" id="chatMessages">
        <div class="chat-empty"><div class="chat-empty-icon">💬</div><h3>Нет сообщений</h3></div>
    </div>

    <div class="chat-input">
        <label for="fileUpload" title="Прикрепить файл">📎</label>
        <input type="file" id="fileUpload" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;" onchange="handleFileSelect()">
        <span id="fileName" style="font-size:12px; color:var(--text-secondary); max-width:100px; overflow:hidden; white-space:nowrap; display:none;"></span>
        <input type="text" id="messageInput" placeholder="Напишите сообщение..." onkeypress="if(event.key==='Enter')sendMessage()" autocomplete="off">
        <button onclick="sendMessage()" style="width:46px;height:46px;background:#4361ee;color:white;border:none;border-radius:50%;cursor:pointer;font-size:18px;flex-shrink:0;">➤</button>
    </div>
</div>

<script>
const orderId = <?= $orderId ?>;
const myId = <?= $_SESSION['user_id'] ?>;
const myName = '<?= htmlspecialchars($_SESSION['user_name'] ?? 'Я', ENT_QUOTES) ?>';
const receiverId = <?= $companion['id'] ?>;
const companionInitial = '<?= mb_substr($companion['company_name'] ?: $companion['name'], 0, 1) ?>';

let lastMessageId = 0;
let lastMessageCount = 0;
let isLoading = false;
let selectedFile = null;

function formatTime(dateString) {
    var date = new Date(dateString);
    return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
}

function isSameDay(d1, d2) {
    return d1.getFullYear() === d2.getFullYear() && d1.getMonth() === d2.getMonth() && d1.getDate() === d2.getDate();
}

function formatDateDivider(dateString) {
    var date = new Date(dateString);
    var today = new Date();
    var yesterday = new Date(today); yesterday.setDate(yesterday.getDate() - 1);
    if (isSameDay(date, today)) return 'Сегодня';
    if (isSameDay(date, yesterday)) return 'Вчера';
    var months = ['янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек'];
    return date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function buildMessagesHTML(messages) {
    if (messages.length === 0) {
        return '<div class="chat-empty"><div class="chat-empty-icon">💬</div><h3>Нет сообщений</h3></div>';
    }
    
    var html = '';
    var lastDate = null;
    
    messages.forEach(function(m, i) {
        var msgDate = new Date(m.created_at);
        var dateStr = formatDateDivider(m.created_at);
        
        if (!lastDate || !isSameDay(lastDate, msgDate)) {
            html += '<div class="chat-date-divider"><span>' + dateStr + '</span></div>';
            lastDate = msgDate;
        }
        
        var isMine = m.sender_id == myId;
        var showAvatar = !isMine && (i === 0 || messages[i-1].sender_id != m.sender_id);
        
        var content = '';
        
        if (m.file_path) {
            if (m.file_type === 'image') {
                content += '<a href="/' + m.file_path + '" target="_blank"><img src="/' + m.file_path + '" style="max-width:250px;max-height:250px;border-radius:12px;cursor:pointer;display:block;" onerror="this.outerHTML=\'📷 Изображение\'"></a>';
            } else {
                content += '<a href="/' + m.file_path + '" target="_blank" style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:rgba(255,255,255,0.1);border-radius:8px;text-decoration:none;color:inherit;"><span style="font-size:24px;">📄</span><span style="font-size:12px;">' + m.file_path.split('/').pop() + '</span></a>';
            }
        }
        
        if (m.message) {
            content += '<span>' + escapeHtml(m.message) + '</span>';
        }
        
        html += '<div class="message-row ' + (isMine ? 'mine' : '') + '" data-id="' + m.id + '">' +
            (showAvatar ? '<div class="message-avatar purple">' + companionInitial + '</div>' : (!isMine ? '<div class="message-avatar" style="visibility:hidden;"></div>' : '')) +
            '<div class="message-content"><div class="message-bubble">' + content + '</div>' +
            '<div class="message-time">' + formatTime(m.created_at) + (isMine ? '<span class="check check-read">✓✓</span>' : '') + '</div></div></div>';
    });
    
    return html;
}

function loadMessages(silent) {
    if (isLoading) return;
    isLoading = true;
    
    fetch('/chat/load/' + orderId)
        .then(function(r) { return r.json(); })
        .then(function(messages) {
            isLoading = false;
            var newCount = messages.length;
            var newLastId = messages.length > 0 ? messages[messages.length - 1].id : 0;
            
            if (newCount === lastMessageCount && newLastId === lastMessageId) return;
            lastMessageCount = newCount;
            lastMessageId = newLastId;
            
            var container = document.getElementById('chatMessages');
            var newHTML = buildMessagesHTML(messages);
            if (container.getAttribute('data-content') === newHTML) return;
            
            var wasAtBottom = container.scrollTop + container.clientHeight >= container.scrollHeight - 50;
            container.setAttribute('data-content', newHTML);
            container.innerHTML = newHTML;
            
            if (wasAtBottom || silent) container.scrollTop = container.scrollHeight;
        })
        .catch(function() { isLoading = false; });
}

function handleFileSelect() {
    var input = document.getElementById('fileUpload');
    var nameEl = document.getElementById('fileName');
    if (input.files.length > 0) {
        selectedFile = input.files[0];
        nameEl.textContent = selectedFile.name;
        nameEl.style.display = 'inline';
    } else {
        selectedFile = null;
        nameEl.style.display = 'none';
    }
}

function sendMessage() {
    var input = document.getElementById('messageInput');
    var message = input.value.trim();
    if (!message && !selectedFile) return;
    
    var formData = new FormData();
    formData.append('message', message);
    formData.append('receiver_id', receiverId);
    if (selectedFile) formData.append('file', selectedFile);
    
    input.value = '';
    document.getElementById('fileName').style.display = 'none';
    selectedFile = null;
    document.getElementById('fileUpload').value = '';
    
    fetch('/chat/send/' + orderId, { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(data) { lastMessageId = 0; loadMessages(true); });
    
    // Оставляем фокус на поле ввода
    input.focus();
}

loadMessages(true);
setInterval(function() { loadMessages(false); }, 3000);
document.getElementById('messageInput').focus();
</script>

<?php require_once BASE_PATH . '/templates/footer.php'; ?>