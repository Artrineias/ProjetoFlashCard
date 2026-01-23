<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudo Flashcards Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="nav-top">
        <a href="admin.php" class="nav-link">⚙ Gerenciar Decks</a>
        <button id="backBtn" class="nav-link" style="border:none; cursor:pointer; display:none;" onclick="location.reload()">← Voltar</button>
    </div>

    <div class="container" id="deck-selection-screen">
        <h2 style="color:white; text-align:center;">Escolha um Deck</h2>
        <div id="deck-grid" class="deck-grid"></div>
    </div>

    <div class="slide-container" id="study-screen" style="display:none;">
        <div id="active-card" class="flashcard">
            <div class="card-header">
                <span id="card-tema" style="font-weight:bold;"></span>
                <div class="stats-box">
                    <span id="stat-err" style="color:#e74c3c;">0 ✖</span>
                    <span id="stat-ok" style="color:#2ecc71; margin-left:10px;">0 ✔</span>
                </div>
            </div>
            
            <div class="card-content">
                <div id="question-display"></div>
                <h3 id="correct-answer-display" style="display:none; margin-top:10px;"></h3>
            </div>

            <div class="input-area">
                <input type="text" id="user-input" placeholder="Sua resposta..." autocomplete="off">
                <button onclick="checkAnswer()" id="btn-action">Responder</button>
            </div>
        </div>
        <div style="text-align:center; color:#ccc; margin-top:10px; font-size:0.8rem;">
            Cards restantes: <span id="cards-count">0</span>
        </div>
    </div>

    <script>
        let cards = [];
        let currentIndex = 0;
        let currentDeckName = '';
        let waitingNext = false;

        document.addEventListener('DOMContentLoaded', listDecks);

        async function listDecks() {
            try {
                const res = await fetch('api.php?action=list');
                const decks = await res.json();
                const grid = document.getElementById('deck-grid');
                grid.innerHTML = '';
                
                if(decks.length === 0) {
                    grid.innerHTML = '<p style="color:white;">Nenhum deck criado.</p>';
                    return;
                }

                decks.forEach(name => {
                    const btn = document.createElement('div');
                    btn.className = 'deck-card';
                    btn.innerHTML = `<strong>${name.toUpperCase()}</strong>`;
                    btn.onclick = () => startStudy(name);
                    grid.appendChild(btn);
                });
            } catch(e) { console.error(e); }
        }

        async function startStudy(name) {
            currentDeckName = name;
            const res = await fetch(`api.php?action=get_deck&name=${name}`);
            const data = await res.json();

            cards = data.sort((a, b) => {
                const errosA = parseInt(a.erros || 0);
                const acertosA = parseInt(a.acertos || 0);
                const scoreA = (errosA * 10) - acertosA;

                const errosB = parseInt(b.erros || 0);
                const acertosB = parseInt(b.acertos || 0);
                const scoreB = (errosB * 10) - acertosB;

                return scoreB - scoreA;
            });

            if(cards.length === 0) return alert('Este deck está vazio!');

            document.getElementById('deck-selection-screen').style.display = 'none';
            document.getElementById('study-screen').style.display = 'block';
            document.getElementById('backBtn').style.display = 'inline-block';
            
            showCard(0);
        }

        function showCard(index) {
            currentIndex = index;
            const card = cards[index];
            const ui = document.getElementById('active-card');
            
            ui.className = 'flashcard'; 
            document.getElementById('correct-answer-display').style.display = 'none';
            document.getElementById('user-input').value = '';
            document.getElementById('user-input').disabled = false;
            document.getElementById('user-input').focus();
            document.getElementById('cards-count').innerText = (cards.length - index);
            
            const btn = document.getElementById('btn-action');
            btn.innerText = 'Responder';
            btn.style.backgroundColor = '#3498db';
            waitingNext = false;

            document.getElementById('card-tema').innerText = card.tema;
            document.getElementById('stat-err').innerText = (card.erros || 0) + ' ✖';
            document.getElementById('stat-ok').innerText = (card.acertos || 0) + ' ✔';

            const display = document.getElementById('question-display');
            const isUrl = card.pergunta.match(/^http/i);
            
            if (isUrl) {
                display.innerHTML = `<img src="${card.pergunta}" class="card-image" onerror="this.style.display='none'; this.parentElement.innerText='${card.pergunta}'">`;
            } else {
                display.innerHTML = `<div class="question-text">${card.pergunta}</div>`;
            }
        }

        async function checkAnswer() {
            if (waitingNext) {
                nextCard();
                return;
            }

            const card = cards[currentIndex];
            const userText = document.getElementById('user-input').value.trim();
            const ui = document.getElementById('active-card');
            const btn = document.getElementById('btn-action');
            const answerDisplay = document.getElementById('correct-answer-display');

            if (userText === "") {
                ui.className = 'flashcard empty';
                return; 
            }

            const isCorrect = userText.toLowerCase() === card.resposta.toLowerCase().trim();

            if (isCorrect) {
                ui.className = 'flashcard correct';
                answerDisplay.innerHTML = " Correto!";
                answerDisplay.style.color = "black";
                
                if(!card.acertos) card.acertos = 0;
                card.acertos++;
                
                await sendUpdate(card.id, 'acertou');
            } else {
                ui.className = 'flashcard wrong';
                answerDisplay.innerHTML = ` Era: <strong>${card.resposta}</strong>`;
                answerDisplay.style.color = "white";
                
                if(!card.erros) card.erros = 0;
                card.erros++;
                
                await sendUpdate(card.id, 'erro');
            }

            document.getElementById('stat-err').innerText = (card.erros || 0) + ' ✖';
            document.getElementById('stat-ok').innerText = (card.acertos || 0) + ' ✔';

            answerDisplay.style.display = 'block';
            document.getElementById('user-input').disabled = true;
            btn.innerText = 'Próximo ➔';
            btn.style.backgroundColor = '#333';
            waitingNext = true;
        }

        function nextCard() {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= cards.length) {
                alert("Ciclo finalizado! Recarregando para reordenar...");
                location.reload(); 
            } else {
                showCard(nextIndex);
            }
        }

        async function sendUpdate(id, status) {
            await fetch('api.php', {
                method: 'POST',
                body: JSON.stringify({ 
                    action: 'update_status', 
                    deck: currentDeckName,
                    id, status 
                })
            });
        }
        
        document.getElementById('user-input').addEventListener("keypress", function(e) {
            if (e.key === "Enter") checkAnswer();
        });
    </script>
</body>
</html>