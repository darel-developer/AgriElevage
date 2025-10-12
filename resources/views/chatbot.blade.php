<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Assistant IA - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #e6f3e6;
        }
        .chatbot-container {
            max-width: 500px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            display: flex;
            flex-direction: column;
            height: 80vh;
        }
        .chatbot-header {
            background: #5fc77a;
            color: #fff;
            border-radius: 1.2rem 1.2rem 0 0;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .chatbot-header .bi-robot {
            font-size: 2rem;
        }
        .chatbot-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.2rem;
            background: #f7fbf7;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .chatbot-message {
            display: flex;
            align-items: flex-end;
            gap: 0.7rem;
        }
        .chatbot-message.user {
            flex-direction: row-reverse;
        }
        .chatbot-message .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            background: #e6f3e6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chatbot-message .bubble {
            max-width: 70%;
            padding: 0.7rem 1rem;
            border-radius: 1rem;
            font-size: 1rem;
            background: #e6f3e6;
            color: #345c37;
        }
        .chatbot-message.user .bubble {
            background: #5fc77a;
            color: #fff;
            border-bottom-right-radius: 0.3rem;
        }
        .chatbot-message.bot .bubble {
            background: #fff;
            color: #345c37;
            border-bottom-left-radius: 0.3rem;
            border: 1px solid #e6f3e6;
        }
        .chatbot-input-area {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e6f3e6;
            background: #fff;
            border-radius: 0 0 1.2rem 1.2rem;
            display: flex;
            gap: 0.7rem;
        }
        .chatbot-input-area input {
            flex: 1;
            border-radius: 0.7rem;
            border: 1px solid #e6f3e6;
            padding: 0.7rem 1rem;
        }
        .chatbot-input-area button {
            background: #5fc77a;
            color: #fff;
            border: none;
            border-radius: 0.7rem;
            padding: 0.7rem 1.2rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .chatbot-input-area button:hover {
            background: #388e3c;
        }
        @media (max-width: 600px) {
            .chatbot-container {
                max-width: 100vw;
                height: 100vh;
                margin: 0;
                border-radius: 0;
            }
            .chatbot-header, .chatbot-input-area {
                padding-left: 0.7rem;
                padding-right: 0.7rem;
            }
        }
    </style>
</head>
<body>
<div class="chatbot-container d-flex flex-column">
    <div class="chatbot-header">
        <i class="bi bi-robot"></i>
        <div>
            <div class="fw-bold">Assistant IA</div>
            <div style="font-size:0.95rem;">Posez vos questions sur l'élevage</div>
        </div>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
        <div class="chatbot-message bot">
            <div class="avatar">
                <i class="bi bi-robot" style="font-size:1.5rem;color:#5fc77a;"></i>
            </div>
            <div class="bubble">
                Bonjour 👋 ! Je suis votre assistant IA AgriElevage.<br>
                Posez-moi une question sur l'élevage ou la gestion de vos animaux.
            </div>
        </div>
    </div>
    <form class="chatbot-input-area" id="chatbot-form" autocomplete="off">
        <input type="text" id="user-input" placeholder="Votre question..." required>
        <button type="submit"><i class="bi bi-send"></i></button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('user-input');
    const messages = document.getElementById('chatbot-messages');

    function addMessage(text, sender = 'user') {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chatbot-message ' + sender;
        msgDiv.innerHTML = `
            <div class="avatar">
                ${sender === 'user'
                    ? '<img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Moi">'
                    : '<i class="bi bi-robot" style="font-size:1.5rem;color:#5fc77a;"></i>'}
            </div>
            <div class="bubble">${text}</div>
        `;
        messages.appendChild(msgDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const question = input.value.trim();
        if (!question) return;
        addMessage(question, 'user');
        input.value = '';
        // Loading message
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'chatbot-message bot';
        loadingMsg.innerHTML = `
            <div class="avatar">
                <i class="bi bi-robot" style="font-size:1.5rem;color:#5fc77a;"></i>
            </div>
            <div class="bubble"><span class="spinner-border spinner-border-sm text-success"></span> ...</div>
        `;
        messages.appendChild(loadingMsg);
        messages.scrollTop = messages.scrollHeight;

        fetch("{{ route('chatbot.message') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message: question })
        })
        .then(res => res.json())
        .then(data => {
            loadingMsg.querySelector('.bubble').textContent = data.reply;
        })
        .catch(() => {
            loadingMsg.querySelector('.bubble').textContent = "Une erreur est survenue. Veuillez réessayer.";
        });
    });
</script>
</body>
</html>
