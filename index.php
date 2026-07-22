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
                    <h2>anotação da questão</h2>
                    <textarea placeholder="Escreva suas anotações aqui..."></textarea>
                </div>
            </div>

            <div class="panel question-area">
                
                <div class="question-box">
                    <h2>Questão flash cards</h2>
                    <div class="answers-grid">
                        <button class="answer-card">
                            <span>resposta A</span>
                        </button>
                        <button class="answer-card">
                            <span>resposta B</span>
                        </button>
                        <button class="answer-card">
                            <span>resposta C</span>
                        </button>
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
                                <p>Aqui fica a dica secreta para te ajudar a resolver a questão.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <aside class="right-column panel">
            <h2>lista do deck<br>flash cards<br>ativos</h2>
            
            <div class="active-questions">
                <button class="outline-btn">Questão 1</button>
                <button class="outline-btn">Questão 2</button>
                <button class="outline-btn">Questão 3</button>
            </div>
        </aside>

    </div>

</body>
</html>