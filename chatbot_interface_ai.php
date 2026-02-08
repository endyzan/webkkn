<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot AI - Desa Brakas Dajah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Chatbot Icon */
        .chatbot-icon {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            z-index: 9999;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .chatbot-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }
        
        .chatbot-icon i {
            font-size: 32px;
            color: white;
        }
        
        .chatbot-icon .notification {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            font-size: 12px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Chatbot Window */
        .chatbot-window {
            position: fixed;
            bottom: 120px;
            right: 30px;
            width: 380px;
            height: 580px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
            z-index: 9998;
            display: none;
            flex-direction: column;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .chatbot-window.active {
            display: flex;
            transform: translateY(0);
            opacity: 1;
        }
        
        .chatbot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        
        .chatbot-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 20px;
            width: 20px;
            height: 20px;
            background: #667eea;
            transform: rotate(45deg);
        }
        
        .chatbot-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .chatbot-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #667eea;
        }
        
        .chatbot-info h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .chatbot-info p {
            margin: 3px 0 0;
            font-size: 12px;
            opacity: 0.9;
        }
        
        .chatbot-controls {
            display: flex;
            gap: 10px;
        }
        
        .chatbot-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .chatbot-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(15deg);
        }
        
        .chatbot-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .welcome-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            margin-bottom: 10px;
        }
        
        .welcome-section h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .welcome-section p {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .message {
            max-width: 85%;
            animation: messageAppear 0.3s ease;
        }
        
        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .user-message {
            margin-left: auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 18px 18px 5px 18px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.2);
        }
        
        .bot-message {
            background: white;
            color: #333;
            padding: 12px 16px;
            border-radius: 18px 18px 18px 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .bot-message::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 10px;
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-right: 8px solid white;
        }
        
        .message-time {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 5px;
            text-align: right;
        }
        
        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 15px;
            background: white;
            border-radius: 18px;
            width: 60px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background: #bdc3c7;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        
        .typing-dot:nth-child(1) { animation-delay: 0s; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        
        .suggestions-section {
            margin-top: 10px;
        }
        
        .suggestions-title {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .suggestions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .suggestion-btn {
            background: white;
            border: 1px solid #e0e0e0;
            color: #2c3e50;
            padding: 10px;
            border-radius: 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .suggestion-btn:hover {
            background: #f0f0f0;
            border-color: #667eea;
            transform: translateY(-2px);
        }
        
        .chatbot-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            background: white;
        }
        
        .chat-input-group {
            display: flex;
            gap: 10px;
        }
        
        .chat-input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .chat-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .chat-send {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .chat-send:hover {
            transform: rotate(90deg);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .chat-send:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .quick-action-btn {
            flex: 1;
            background: white;
            border: 1px solid #e0e0e0;
            padding: 10px;
            border-radius: 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .quick-action-btn:hover {
            background: #f8f9fa;
            border-color: #667eea;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .chatbot-window {
                width: calc(100vw - 40px);
                right: 20px;
                bottom: 100px;
                height: 70vh;
            }
            
            .chatbot-icon {
                right: 20px;
                bottom: 20px;
                width: 60px;
                height: 60px;
            }
            
            .suggestions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Chatbot Icon -->
    <div class="chatbot-icon" id="chatbotIcon">
        <i class="fas fa-robot"></i>
        <div class="notification" id="notificationBadge" style="display:none">!</div>
    </div>
    
    <!-- Chatbot Window -->
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="chatbot-title">
                <div class="chatbot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chatbot-info">
                    <h3>Asisten Desa AI</h3>
                    <p>Online • Siap membantu</p>
                </div>
            </div>
            <div class="chatbot-controls">
                <button class="chatbot-btn" id="minimizeChat">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="chatbot-btn" id="closeChat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="chatbot-body" id="chatbotBody">
            <!-- Welcome message -->
            <div class="welcome-section">
                <h4>Asisten Desa Brakas Dajah</h4>
                <p>Saya asisten virtual dengan AI yang siap membantu Anda 24/7 dengan informasi tentang administrasi, data penduduk, bansos, dan layanan desa lainnya.</p>
            </div>
            
            <!-- Bot intro message -->
            <div class="message bot-message">
                Halo! Saya Asisten Ai Desa Brakas Dajah. Saya bisa membantu Anda dengan:
                <br><br>
                ✅ <strong>Informasi Administrasi</strong>: KTP, KK, surat-surat
                <br>
                ✅ <strong>Data Real-time</strong>: Penduduk, APBDes, bansos
                <br>
                ✅ <strong>Layanan Desa</strong>: Jadwal, kontak, prosedur
                <br>
                ✅ <strong>Q&A Cerdas</strong>: Pahami pertanyaan natural
                <br><br>
                Silakan tanyakan apa yang Anda butuhkan!
                <div class="message-time">Sekarang</div>
            </div>
            
            <!-- Suggestions will be loaded here -->
            <div class="suggestions-section" id="suggestionsSection">
                <div class="suggestions-title">Coba tanyakan:</div>
                <div class="suggestions-grid" id="suggestionsGrid">
                    <!-- Suggestions will be loaded by JavaScript -->
                </div>
            </div>
        </div>
        
        <div class="chatbot-footer">
            <div class="quick-actions">
                <button class="quick-action-btn" onclick="sendQuickQuestion('Bagaimana cara mengurus KTP?')">
                    <i class="fas fa-id-card"></i> Urus KTP
                </button>
                <button class="quick-action-btn" onclick="sendQuickQuestion('Berapa jumlah penduduk?')">
                    <i class="fas fa-users"></i> Data Penduduk
                </button>
                <button class="quick-action-btn" onclick="sendQuickQuestion('Apa saja bansos yang ada?')">
                    <i class="fas fa-hand-holding-heart"></i> Bansos
                </button>
            </div>
            
            <div class="chat-input-group">
                <input type="text" class="chat-input" id="chatInput" 
                       placeholder="Tanyakan tentang administrasi desa..." 
                       onkeypress="handleKeyPress(event)">
                <button class="chat-send" id="sendMessage">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // State management
        const chatState = {
            isOpen: false,
            isTyping: false,
            messages: [],
            sessionId: null
        };
        
        // Elements
        const chatbotIcon = document.getElementById('chatbotIcon');
        const chatbotWindow = document.getElementById('chatbotWindow');
        const chatbotBody = document.getElementById('chatbotBody');
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');
        const suggestionsGrid = document.getElementById('suggestionsGrid');
        const notificationBadge = document.getElementById('notificationBadge');
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            generateSessionId();
            loadSuggestions();
            
            // Auto-open after 10 seconds
            setTimeout(() => {
                if(!chatState.isOpen) {
                    showNotification();
                }
            }, 10000);
        });
        
        // Generate unique session ID
        function generateSessionId() {
            chatState.sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        }
        
        // Toggle chatbot
        chatbotIcon.addEventListener('click', toggleChatbot);
        document.getElementById('closeChat').addEventListener('click', closeChatbot);
        document.getElementById('minimizeChat').addEventListener('click', closeChatbot);
        
        function toggleChatbot() {
            if(!chatState.isOpen) {
                openChatbot();
            } else {
                closeChatbot();
            }
        }
        
        function openChatbot() {
            chatbotWindow.classList.add('active');
            chatState.isOpen = true;
            hideNotification();
            chatInput.focus();
        }
        
        function closeChatbot() {
            chatbotWindow.classList.remove('active');
            chatState.isOpen = false;
        }
        
        function showNotification() {
            notificationBadge.style.display = 'flex';
        }
        
        function hideNotification() {
            notificationBadge.style.display = 'none';
        }
        
        // Load suggestions
        function loadSuggestions() {
            fetch('./chatbot_ai.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_suggestions'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    suggestionsGrid.innerHTML = '';
                    data.suggestions.forEach(question => {
                        const button = document.createElement('button');
                        button.className = 'suggestion-btn';
                        button.textContent = question;
                        button.onclick = () => sendQuickQuestion(question);
                        suggestionsGrid.appendChild(button);
                    });
                }
            });
        }
        
        // Send message
        sendMessage.addEventListener('click', sendUserMessage);
        
        function handleKeyPress(e) {
            if(e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendUserMessage();
            }
        }
        
        function sendUserMessage() {
            const message = chatInput.value.trim();
            if(message === '' || chatState.isTyping) return;
            
            // Add user message
            addMessage(message, 'user');
            chatInput.value = '';
            
            // Show typing indicator
            showTypingIndicator();
            
            // Send to server
            fetch('./chatbot_ai.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=send_message&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                hideTypingIndicator();
                
                if(data.status === 'success') {
                    // Add bot response
                    addMessage(data.response, 'bot');
                    
                    // Reload suggestions
                    setTimeout(loadSuggestions, 500);
                } else {
                    addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
                }
            })
            .catch(error => {
                hideTypingIndicator();
                addMessage('Koneksi terputus. Silakan refresh halaman.', 'bot');
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
            
            // Format text with line breaks
            const formattedText = text.replace(/\n/g, '<br>');
            
            messageDiv.innerHTML = `
                ${formattedText}
                <div class="message-time">${timestamp}</div>
            `;
            
            chatbotBody.appendChild(messageDiv);
            scrollToBottom();
            
            // Save to state
            chatState.messages.push({
                sender,
                text,
                time: timestamp
            });
        }
        
        function showTypingIndicator() {
            chatState.isTyping = true;
            sendMessage.disabled = true;
            
            const typingDiv = document.createElement('div');
            typingDiv.className = 'typing-indicator';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            `;
            
            chatbotBody.appendChild(typingDiv);
            scrollToBottom();
        }
        
        function hideTypingIndicator() {
            chatState.isTyping = false;
            sendMessage.disabled = false;
            
            const typingIndicator = document.getElementById('typingIndicator');
            if(typingIndicator) {
                typingIndicator.remove();
            }
        }
        
        function scrollToBottom() {
            setTimeout(() => {
                chatbotBody.scrollTop = chatbotBody.scrollHeight;
            }, 100);
        }
        
        // Voice recognition (optional)
        let recognition = null;
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'id-ID';
            
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                chatInput.value = transcript;
                sendUserMessage();
            };
            
            recognition.onerror = function(event) {
                console.log('Voice recognition error:', event.error);
            };
        }
        
        // Function to start voice input
        function startVoiceInput() {
            if(recognition) {
                recognition.start();
            } else {
                addMessage('Browser Anda tidak mendukung input suara.', 'bot');
            }
        }
        
        // Add voice button to quick actions if supported
        if(recognition) {
            const quickActions = document.querySelector('.quick-actions');
            const voiceButton = document.createElement('button');
            voiceButton.className = 'quick-action-btn';
            voiceButton.innerHTML = '<i class="fas fa-microphone"></i> Suara';
            voiceButton.onclick = startVoiceInput;
            quickActions.appendChild(voiceButton);
        }
    </script>
</body>
</html>