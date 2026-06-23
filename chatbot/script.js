// =====================================================
// FUNGSI UTAMA
// =====================================================

function getNow() {
    const now = new Date();
    return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function appendMsg(type, text, time) {
    const box = document.getElementById('chat-box');
    const isBot = (type === 'bot');

    const row = document.createElement('div');
    row.className = 'msg-row ' + (isBot ? '' : 'user-row');

    const av = document.createElement('div');
    av.className = 'msg-avatar ' + (isBot ? 'bot-av' : 'user-av');
    av.textContent = isBot ? '🤖' : '👤';

    const content = document.createElement('div');
    content.className = 'msg-content';

    const bubble = document.createElement('div');
    bubble.className = 'msg-bubble ' + (isBot ? 'bot-bubble' : 'user-bubble');

    if (isBot) {
        bubble.innerHTML = text;
    } else {
        bubble.textContent = text;
    }

    const timeEl = document.createElement('div');
    timeEl.className = 'msg-time';
    timeEl.textContent = time || getNow();

    content.appendChild(bubble);
    content.appendChild(timeEl);
    row.appendChild(av);
    row.appendChild(content);

    box.appendChild(row);
    box.scrollTop = box.scrollHeight;
}

function showTyping() {
    const box = document.getElementById('chat-box');
    const typing = document.createElement('div');
    typing.className = 'typing-indicator';
    typing.id = 'typing';

    const av = document.createElement('div');
    av.className = 'msg-avatar bot-av';
    av.textContent = '🤖';

    const bubble = document.createElement('div');
    bubble.className = 'typing-bubble';
    bubble.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';

    typing.appendChild(av);
    typing.appendChild(bubble);
    box.appendChild(typing);
    box.scrollTop = box.scrollHeight;
}

function hideTyping() {
    const el = document.getElementById('typing');
    if (el) el.remove();
}

// =====================================================
// KIRIM PESAN
// =====================================================

function kirimPesan() {
    const input = document.getElementById('pesan');
    const pesan = input.value.trim();
    if (!pesan) return;

    appendMsg('user', pesan, getNow());
    input.value = '';
    input.disabled = true;

    showTyping();

    const formData = new FormData();
    formData.append('pesan', pesan);

    // Delay 600ms untuk efek natural
    setTimeout(() => {
        fetch('chatbot.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            hideTyping();
            appendMsg('bot', data.jawaban, getNow());
            input.disabled = false;
            input.focus();
        })
        .catch(() => {
            hideTyping();
            appendMsg('bot', '⚠️ Terjadi kesalahan. Silakan coba lagi.', getNow());
            input.disabled = false;
        });
    }, 600);
}

// =====================================================
// QUICK REPLY
// =====================================================

function quickReply(teks) {
    document.getElementById('pesan').value = teks;
    kirimPesan();
}

// =====================================================
// ENTER TO SEND
// =====================================================

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('pesan').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            kirimPesan();
        }
    });
});