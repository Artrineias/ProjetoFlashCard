<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface de Flashcards</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="main-container">
        <!-- Coluna da Esquerda (Decks e Questão) -->
        <div class="left-column">
            <!-- Topo: Decks e Anotações -->
            <div class="top-row">
                <div class="box deck-list">
                    <h2>Lista de Decks</h2>
                    <input type="text" placeholder="Nome do deck...">
                    <button>Adicionar Deck</button>
                </div>
                <div class="box note-section">
                    <h2>Anotações da Questão</h2>
                    <textarea placeholder="Digite suas anotações aqui..."></textarea>
                </div>
            </div>

            <!-- Centro: Área da Questão Atual -->
            <div class="box question-area">
                <h1 class="main-title">Flashcard Atual: [Título da Questão]</h1>
                
                <div class="card-grid">
                    <div class="card response-card">
                        <h3>Resposta A</h3>
                        <p>[Conteúdo da resposta 1]</p>
                    </div>
                    <div class="card response-card">
                        <h3>Resposta B</h3>
                        <p>[Conteúdo da resposta 1]</p>
                    </div>
                    <div class="card response-card">
                        <h3>Resposta C</h3>
                        <p>[Conteúdo da resposta 1]</p>
                    </div>
                    <div class="card hint-card">
                        <h3>Dica da questão</h3>
                        <p>Dica para esta questão... Dica para esta questão e esta questão...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita (Flashcards Ativos) -->
        <div class="right-column box active-list">
            <h2>Lista de Flashcards Ativos</h2>
            <ul>
                <li><input type="checkbox"> História 101</li>
                <li><input type="checkbox"> Química Orgânica</li>
                <li><input type="checkbox"> Contracepção da Questão</li>
                <li><input type="checkbox"> Anotação da Questão</li>
                <li><input type="checkbox"> Resposta 101</li>
                <li><input type="checkbox"> Química Orgânica</li>
                <li><input type="checkbox"> História de Flashcards</li>
                <li><input type="checkbox"> História 101</li>
            </ul>
        </div>
    </div>

</body>
</html>
