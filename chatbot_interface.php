<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot - Desa Brakas Dajah</title>
    <style>
        /* Chatbot Icon */
        .chatbot-icon {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #2c3e50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            transition: all 0.3s ease;
        }
        
        .chatbot-icon:hover {
            background: #1a252f;
            transform: scale(1.1);
        }
        
        .chatbot-icon i {
            font-size: 28px;
            color: white;
        }
        
        .chatbot-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            font-size: 12px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Chatbot Window */
        .chatbot-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 9998;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        
        .chatbot-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chatbot-header h3 {
            margin: 0;
            font-size: 16px;
        }
        
        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        
        .chatbot-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }
        
        .message {
            margin-bottom: 15px;
            max-width: 80%;
        }
        
        .user-message {
            margin-left: auto;
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 15px 15px 5px 15px;
        }
        
        .bot-message {
            background: white;
            color: #333;
            padding: 10px 15px;
            border-radius: 15px 15px 15px 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .chatbot-footer {
            padding: 15px;
            border-top: 1px solid #eee;
            background: white;
        }
        
        .quick-questions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .quick-btn {
            background: #e8f4fc;
            border: 1px solid #3498db;
            color: #3498db;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .quick-btn:hover {
            background: #3498db;
            color: white;
        }
        
        .chat-input-group {
            display: flex;
            gap: 10px;
        }
        
        .chat-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
        }
        
        .chat-send {
            background: #3498db;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .chat-send:hover {
            background: #2980b9;
        }
        
        .welcome-message {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .welcome-message h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .timestamp {
            font-size: 11px;
            color: #7f8c8d;
            margin-top: 5px;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Chatbot Icon -->
    <div class="chatbot-icon" id="chatbotIcon">
        <i>💬</i>
        <div class="badge" id="messageBadge" style="display:none">1</div>
    </div>
    
    <!-- Chatbot Window -->
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <h3>🤖 Asisten Desa Brakas Dajah</h3>
            <button class="chatbot-close" id="closeChatbot">&times;</button>
        </div>
        
        <div class="chatbot-body" id="chatbotBody">
            <!-- Messages will be loaded here -->
        </div>
        
        <div class="chatbot-footer">
            <div class="quick-questions" id="quickQuestions">
                <!-- Quick question buttons will be loaded here -->
            </div>
            
            <div class="chat-input-group">
                <input type="text" class="chat-input" id="chatInput" placeholder="Tanyakan sesuatu..." onkeypress="handleKeyPress(event)">
                <button class="chat-send" id="sendMessage">➤</button>
            </div>
        </div>
    </div>

    <script>
        let isChatOpen = false;
        
        // Elements
        const chatbotIcon = document.getElementById('chatbotIcon');
        const chatbotWindow = document.getElementById('chatbotWindow');
        const closeChatbot = document.getElementById('closeChatbot');
        const chatbotBody = document.getElementById('chatbotBody');
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');
        const quickQuestions = document.getElementById('quickQuestions');
        
        // Toggle chatbot
        chatbotIcon.addEventListener('click', () => {
            if(!isChatOpen) {
                openChatbot();
            } else {
                closeChatbotWindow();
            }
        });
        
        closeChatbot.addEventListener('click', closeChatbotWindow);
        
        function openChatbot() {
            chatbotWindow.style.display = 'flex';
            isChatOpen = true;
            loadInitialMessages();
            chatInput.focus();
        }
        
        function closeChatbotWindow() {
            chatbotWindow.style.display = 'none';
            isChatOpen = false;
        }
        
        // Load initial messages
        function loadInitialMessages() {
            const welcomeMsg = `
                <div class="welcome-message">
                    <h4>👋 Halo! Saya Asisten Desa</h4>
                    <p>Saya siap membantu Anda dengan informasi tentang:</p>
                    <p>• Administrasi Kependudukan<br>
                    • Layanan Desa<br>
                    • Data Penduduk<br>
                    • APBDes<br>
                    • Berita Terbaru</p>
                    <small>Tanyakan apa saja terkait Desa Brakas Dajah!</small>
                </div>
            `;
            
            chatbotBody.innerHTML = welcomeMsg;
            loadQuickQuestions();
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }
        
        // Load quick questions
        function loadQuickQuestions() {
            fetch('./chatbot.php')
                .then(response => response.text())
                .then(html => {
                    // Extract quick questions from PHP response
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const questions = tempDiv.querySelector('#quickQuestionsData');
                    
                    if(questions) {
                        const questionsData = JSON.parse(questions.textContent);
                        quickQuestions.innerHTML = '';
                        
                        questionsData.forEach(question => {
                            const btn = document.createElement('button');
                            btn.className = 'quick-btn';
                            btn.textContent = question;
                            btn.onclick = () => sendQuickQuestion(question);
                            quickQuestions.appendChild(btn);
                        });
                    }
                });
        }
        
        // Send message
        sendMessage.addEventListener('click', sendUserMessage);
        
        function handleKeyPress(e) {
            if(e.key === 'Enter') {
                sendUserMessage();
            }
        }
        
        function sendUserMessage() {
            const message = chatInput.value.trim();
            if(message === '') return;
            
            // Add user message to chat
            addMessage(message, 'user');
            chatInput.value = '';
            
            // Send to server
            fetch('./chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=send_message&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Add bot response
                    setTimeout(() => {
                        addMessage(data.response, 'bot');
                    }, 500);
                }
            });
        }
        
        function sendQuickQuestion(question) {
            chatInput.value = question;
            sendUserMessage();
        }
        
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}-message`;
            
            const timestamp = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            messageDiv.innerHTML = `
                ${text}
                <div class="timestamp">${timestamp}</div>
            `;
            
            chatbotBody.appendChild(messageDiv);
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }
        
        // Auto-open chatbot on page load (optional)
        // setTimeout(() => {
        //     if(!isChatOpen) {
        //         openChatbot();
        //     }
        // }, 3000);
    </script>
</body>
</html>