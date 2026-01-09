document.addEventListener("DOMContentLoaded", function () {

    // Mobile Menu Toggle
    document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
        document.getElementById('mainMenu')?.classList.toggle('show');
    });

    // Smooth Scrolling
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (!targetElement) return;

            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });

            document.getElementById('mainMenu')?.classList.remove('show');

            document.querySelectorAll('nav a').forEach(link => {
                link.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Tabs
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(tabId)?.classList.add('active');
        });
    });

    // Active nav on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('nav a');
        let currentSection = '';

        sections.forEach(section => {
            if (pageYOffset >= section.offsetTop - 100) {
                currentSection = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + currentSection) {
                link.classList.add('active');
            }
        });
    });

    // Dropdown mobile
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.nextElementSibling?.classList.toggle('active');
            }
        });
    });

    // ================= CHAT =================
    const chatToggle = document.getElementById("chatToggle");
    const chatPopup = document.getElementById("chatPopup");
    const chatClose = document.getElementById("chatClose");
    const chatSend = document.getElementById("chatSend");
    const chatInput = document.getElementById("chatInput");
    const chatBody = document.querySelector(".chat-body");

    if (chatToggle && chatPopup) {
        chatToggle.onclick = () => chatPopup.style.display = "flex";
    }

    if (chatClose) {
        chatClose.onclick = () => chatPopup.style.display = "none";
    }

    if (chatSend) {
        chatSend.onclick = sendMessage;
    }

    if (chatInput) {
        chatInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") sendMessage();
        });
    }

    function sendMessage() {
        if (!chatInput || !chatBody) return;

        const message = chatInput.value.trim();
        if (!message) return;

        const userMsg = document.createElement("div");
        userMsg.className = "chat-message user";
        userMsg.innerHTML = `<p>${message}</p>`;
        userMsg.querySelector("p").style.background = "#c8f7c5";

        chatBody.appendChild(userMsg);
        chatBody.scrollTop = chatBody.scrollHeight;
        chatInput.value = "";
    }

});
