<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface de Flashcards - Dark Theme</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="main-container">

        <main class="left-column">

            <div class="top-row">
                <div class="panel decks-panel">
                    <h2>lista decks Flash cards</h2>
                    <div class="deck-buttons">
                        <button class="outline-btn">deck de cards 1</button>
                        <button class="outline-btn">deck de cards 2</button>
                    </div>
                </div>

                <div class="panel note-panel">
                    <h2>Anotação da questão</h2>
                    <textarea id="note-input" placeholder="Escreva suas anotações aqui..." style="display: none;"></textarea>
                    <p id="note-msg">As anotações serão liberadas após responder.</p>
                </div>
            </div>

            <div class="panel question-area">

<div class="question-box">
    <h2 id="question-text">Carregando questão...</h2>
    
    <div class="answers-grid" id="answers-container">

    </div>

    <div class="question-controls">
        <button id="btn-prev" class="nav-btn">⬅️ Anterior</button>
        <button id="btn-next" class="nav-btn">Próxima ➡️</button>
    </div>
</div>


                <div class="hint-box">
                    <div class="flip-card">
                        <div class="flip-card-inner">

                            <div class="flip-card-front">
                                <h2>dica da questão</h2>
                                <p>(Passe o mouse para virar)</p>
                            </div>

                            <div class="flip-card-back">
                                <h2>A Dica!</h2>
                                <p id="hint-text">Carregando dica...</p>
                            </div>
                            <div class="active-questions" id="deck-list-container">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <aside class="right-column panel">
            <h2>lista do deck<br>flash cards<br>ativos</h2>
            

            <div class="active-questions" id="deck-list-container">

            </div>
        </aside>

    </div>
    <script src="script.js"></script>
</body>

</html>