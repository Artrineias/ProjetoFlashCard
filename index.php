<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modo Estudo</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="nav-top">
        <a href="admin.php" class="nav-link">← Adicionar Cards</a>
    </div>

    <div class="slide-container">
        <div id="active-card" class="flashcard">
            <div class="card-header">
                <span id="card-tema">Tema</span>
                <span id="card-dif">Dificuldade</span>
            </div>
            
            <div class="card-content">
                <div id="question-display"></div>
                
                <h3 id="correct-answer-display" style="display:none; margin-top:10px;"></h3>
            </div>

            <div class="input-area" id="input-area">
                <input type="text" id="user-input" placeholder="Digite sua resposta aqui..." autocomplete="off">
                <button onclick="checkAnswer()" id="btn-action">Responder</button>
            </div>
        </div>
    </div>

    <script>
        let cards = [];
        let currentIndex = 0;
        let waitingNext = false; // Estado para saber se o botão vira "Próximo"

        document.addEventListener('DOMContentLoaded', loadDeck);

        async function loadDeck() {
            try {
                const req = await fetch('data.json');
                const data = await req.json();
                
                // Ordena: Quem tem mais erros aparece primeiro
                cards = data.sort((a, b) => (b.erros || 0) - (a.erros || 0));

                if (cards.length > 0) {
                    showCard(0);
                } else {
                    document.querySelector('.slide-container').innerHTML = '<h2 style="color:white; text-align:center;">Nenhum card encontrado.<br><a href="admin.php" style="color:#3498db">Crie alguns aqui</a></h2>';
                }
            } catch (e) {
                console.log("Erro ao carregar dados");
            }
        }

        function showCard(index) {
            currentIndex = index;
            const card = cards[index];
            const ui = document.getElementById('active-card');
            
            // Reseta visual
            ui.className = 'flashcard'; 
            document.getElementById('correct-answer-display').style.display = 'none';
            document.getElementById('user-input').value = '';
            document.getElementById('user-input').disabled = false;
            document.getElementById('user-input').focus();
            
            // Reseta botão
            const btn = document.getElementById('btn-action');
            btn.innerText = 'Responder';
            btn.style.backgroundColor = '#3498db';
            waitingNext = false;

            // Preenche dados
            document.getElementById('card-tema').innerText = card.tema;
            document.getElementById('card-dif').innerText = card.dificuldade + ` (${card.erros || 0} erros)`;

            // Lógica de Imagem Melhorada
            const display = document.getElementById('question-display');
            // Verifica se parece URL de imagem (termina com extensão ou começa com http)
            const isUrl = card.pergunta.match(/^http/i);
            
            if (isUrl) {
                // Tenta carregar imagem, se der erro, mostra o texto
                display.innerHTML = `<img src="${card.pergunta}" class="card-image" onerror="this.style.display='none'; this.parentElement.innerText='${card.pergunta}'">`;
            } else {
                display.innerHTML = `<div class="question-text">${card.pergunta}</div>`;
            }
        }

        async function checkAnswer() {
            if (waitingNext) {
                // Se já respondeu, o botão serve para ir para o próximo
                nextCard();
                return;
            }

            const card = cards[currentIndex];
            const userText = document.getElementById('user-input').value.trim();
            const ui = document.getElementById('active-card');
            const btn = document.getElementById('btn-action');
            const answerDisplay = document.getElementById('correct-answer-display');

            // 1. Caso Vazio (Amarelo)
            if (userText === "") {
                ui.className = 'flashcard empty';
                alert("Digite uma resposta!"); // Opcional
                return; 
            }

            // Normaliza para comparar (minúsculo e sem espaços extras)
            const isCorrect = userText.toLowerCase() === card.resposta.toLowerCase().trim();

            if (isCorrect) {
                // 2. Caso Correto (Verde + Preto)
                ui.className = 'flashcard correct';
                answerDisplay.innerHTML = "✅ Resposta Correta!";
                answerDisplay.style.color = "black";
                await sendUpdate(card.id, 'acertou');
            } else {
                // 3. Caso Errado (Vermelho)
                ui.className = 'flashcard wrong';
                answerDisplay.innerHTML = `❌ A resposta era: <strong>${card.resposta}</strong>`;
                answerDisplay.style.color = "white"; // Para ler no vermelho
                await sendUpdate(card.id, 'erro');
            }

            // Mostra resposta correta e muda estado do botão
            answerDisplay.style.display = 'block';
            document.getElementById('user-input').disabled = true;
            btn.innerText = 'Próximo ➔';
            btn.style.backgroundColor = '#333';
            waitingNext = true;
        }

        function nextCard() {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= cards.length) {
                alert("Você completou todos os cards! Começando de novo...");
                nextIndex = 0;
                // Recarrega deck para reordenar baseados nos novos erros
                loadDeck(); 
            } else {
                showCard(nextIndex);
            }
        }

        async function sendUpdate(id, status) {
            await fetch('api.php', {
                method: 'POST',
                body: JSON.stringify({ action: 'update', id, status })
            });
        }

        // Permitir Enter para enviar
        document.getElementById('user-input').addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                checkAnswer();
            }
        });
    </script>
</body>
</html>