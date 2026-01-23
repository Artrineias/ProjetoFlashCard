<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Decks</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .layout { display: flex; gap: 20px; max-width: 900px; margin: 0 auto; }
        .sidebar { width: 30%; background: white; padding: 20px; border-radius: 10px; }
        .main-form { width: 70%; background: white; padding: 20px; border-radius: 10px; display: none; }
        .deck-item { padding: 10px; background: #f0f2f5; margin-bottom: 5px; cursor: pointer; border-radius: 5px; }
        .deck-item:hover, .deck-item.active { background: #3498db; color: white; }
        h3 { margin-top: 0; }
        input { width: 100%; box-sizing: border-box; margin-bottom: 10px; padding: 10px; }
        button { width: 100%; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #3498db; text-decoration: none; }
    </style>
</head>
<body>

    <h2 style="text-align: center; color: white;">Painel Admin</h2>

    <div class="layout">
        <div class="sidebar">
            <h3>Seus Decks</h3>
            <div style="display: flex; gap: 5px; margin-bottom: 15px;">
                <input type="text" id="newDeckName" placeholder="Nome do novo deck" style="margin:0;">
                <button onclick="createDeck()" style="width: auto;">+</button>
            </div>
            <div id="deck-list"></div>
            <a href="index.php" class="back-link">Ir para Estudo</a>
        </div>

        <div class="main-form" id="cardForm">
            <h3 id="selectedDeckTitle">Adicionar Card</h3>
            <label>Tema</label>
            <input type="text" id="tema">
            <label>Pergunta / URL Imagem</label>
            <input type="text" id="pergunta">
            <label>Resposta</label>
            <input type="text" id="resposta">
            <button onclick="addCard()" style="background-color: #27ae60;">Salvar Card</button>
        </div>
    </div>

    <script>
        let currentDeck = null;

        document.addEventListener('DOMContentLoaded', loadDecks);

        async function loadDecks() {
            const res = await fetch('api.php?action=list');
            const decks = await res.json();
            const list = document.getElementById('deck-list');
            list.innerHTML = '';

            decks.forEach(name => {
                const div = document.createElement('div');
                div.className = 'deck-item';
                div.innerText = name;
                div.onclick = () => selectDeck(name, div);
                list.appendChild(div);
            });
        }

        async function createDeck() {
            const name = document.getElementById('newDeckName').value;
            if(!name) return;
            
            await fetch('api.php', {
                method: 'POST',
                body: JSON.stringify({ action: 'create_deck', name })
            });
            document.getElementById('newDeckName').value = '';
            loadDecks();
        }

        function selectDeck(name, el) {
            currentDeck = name;
            document.querySelectorAll('.deck-item').forEach(d => d.classList.remove('active'));
            el.classList.add('active');
            
            document.getElementById('cardForm').style.display = 'block';
            document.getElementById('selectedDeckTitle').innerText = 'Editando: ' + name;
        }

        async function addCard() {
            if(!currentDeck) return alert('Selecione um deck');
            
            const tema = document.getElementById('tema').value;
            const pergunta = document.getElementById('pergunta').value;
            const resposta = document.getElementById('resposta').value;

            if(!pergunta || !resposta) return;

            const res = await fetch('api.php', {
                method: 'POST',
                body: JSON.stringify({
                    action: 'add_card',
                    deck: currentDeck,
                    tema, pergunta, resposta
                })
            });

            if((await res.json()).status === 'success') {
                alert('Salvo em ' + currentDeck);
                document.getElementById('pergunta').value = '';
                document.getElementById('resposta').value = '';
            }
        }
    </script>
</body>
</html>