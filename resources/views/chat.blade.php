<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AIチャット</title>
    <style>
        body { font-family: sans-serif; }
        #chat-container { max-width: 600px; margin: 20px auto; border: 1px solid #ccc; padding: 10px; }
        #chat-box { height: 400px; overflow-y: scroll; border-bottom: 1px solid #ccc; margin-bottom: 10px; padding: 5px; }
        .message { margin-bottom: 10px; }
        .user-message { text-align: right; color: blue; }
        .ai-message { text-align: left; color: green; }
        #chat-form { display: flex; }
        #message-input { flex-grow: 1; padding: 5px; }
        #send-button { padding: 5px 10px; }
    </style>
</head>
<body>

<div id="chat-container">
    <h2>AIチャット</h2>
    <div id="chat-box">
        </div>
    <form id="chat-form">
        <input type="text" id="message-input" placeholder="メッセージを入力..." autocomplete="off">
        <button type="submit" id="send-button">送信</button>
    </form>
</div>

<script>
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const chatBox = document.getElementById('chat-box');
    const sendButton = document.getElementById('send-button');

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault(); 

        const userMessage = messageInput.value.trim();
        if (userMessage === '') return;

        appendMessage('あなた', userMessage, 'user-message');
        messageInput.value = '';
        sendButton.disabled = true; 

        try {
            const response = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: userMessage })
            });

            if (!response.ok) {
                throw new Error('ネットワークエラーが発生しました。');
            }

            const data = await response.json();

            appendMessage('AI', data.reply, 'ai-message');

        } catch (error) {
            console.error('エラー:', error);
            appendMessage('エラー', 'メッセージの送信に失敗しました。', 'ai-message');
        } finally {
            sendButton.disabled = false; 
        }
    });

    function appendMessage(sender, message, className) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', className);
        messageElement.innerHTML = `<strong>${sender}:</strong><p>${message.replace(/\n/g, '<br>')}</p>`;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight; 
    }
</script>

</body>
</html>