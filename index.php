<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Flashcards</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div id="form-container">
        <h3>Adicionar Novo Card</h3>
        <input type="text" id="frontInput" placeholder="Frente (Texto ou URL da Imagem)">
        <input type="text" id="backInput" placeholder="Verso (Resposta)">
        <button onclick="addCard()">Adicionar</button>
    </div>

    <div id="cards-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', loadCards);

        async function loadCards() {
            const response = await fetch('data.json');
            const data = await response.json();
            const container = document.getElementById('cards-container');
            container.innerHTML = '';

            data.forEach(item => {
                const card = createCardElement(item);
                container.appendChild(card);
            });
        }

        function createCardElement(item) {
            const card = document.createElement('div');
            card.className = 'card';
            card.onclick = () => card.classList.toggle('flipped');

            const isImage = (text) => {
                return text.match(/\.(jpeg|jpg|gif|png)$/) != null;
            };

            const frontContent = isImage(item.front) 
                ? `<img src="${item.front}" alt="Flashcard Image">` 
                : item.front;

            card.innerHTML = `
                <div class="card-inner">
                    <div class="card-front">
                        ${frontContent}
                    </div>
                    <div class="card-back">
                        ${item.back}
                    </div>
                </div>
            `;
            return card;
        }

        async function addCard() {
            const front = document.getElementById('frontInput').value;
            const back = document.getElementById('backInput').value;

            if (!front || !back) return;

            const newCard = { front, back };

            const response = await fetch('save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(newCard)
            });

            const result = await response.json();

            if (result.status === 'success') {
                document.getElementById('frontInput').value = '';
                document.getElementById('backInput').value = '';
                loadCards();
            }
        }
    </script>
</body>
</html>