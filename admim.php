<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Flashcards</title>
    <link rel="stylesheet" href="index.css">
    <style>
        /* Estilo específico simples para o admin */
        body { background-color: #f4f6f8; color: #333; }
        .admin-box { background: white; padding: 40px; border-radius: 10px; width: 100%; max-width: 500px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, textarea { width: 100%; box-sizing: border-box; margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-green { background-color: #27ae60; width: 100%; }
        .link-study { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #3498db; }
    </style>
</head>
<body>

    <div class="admin-box">
        <h2>Adicionar Novo Card</h2>
        
        <label>Tema</label>
        <input type="text" id="tema" placeholder="Ex: Geografia">
        
        <label>Pergunta ou Link da Imagem</label>
        <input type="text" id="pergunta" placeholder="Cole a URL da imagem ou digite o texto">
        
        <label>Resposta Correta</label>
        <input type="text" id="resposta" placeholder="A resposta exata">

        <button class="btn-green" onclick="createCard()">Salvar Card</button>

        <a href="index.php" class="link-study">Ir para o Modo de Estudo (Slide) →</a>
    </div>

    <script>
        async function createCard() {
            const tema = document.getElementById('tema').value;
            const pergunta = document.getElementById('pergunta').value;
            const resposta = document.getElementById('resposta').value;

            if(!pergunta || !resposta) return alert('Preencha pergunta e resposta');

            const res = await fetch('api.php', {
                method: 'POST',
                body: JSON.stringify({
                    action: 'create',
                    tema, pergunta, resposta
                })
            });

            const data = await res.json();
            if(data.status === 'success') {
                alert('Salvo!');
                document.getElementById('pergunta').value = '';
                document.getElementById('resposta').value = '';
            }
        }
    </script>
</body>
</html>