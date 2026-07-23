
let deck = [];
let questaoAtualIndex = 0;
let questaoRespondida = false;


const questionText = document.getElementById('question-text');
const answersContainer = document.getElementById('answers-container');
const hintText = document.getElementById('hint-text');
const noteInput = document.getElementById('note-input');
const noteMsg = document.getElementById('note-msg');
const deckListContainer = document.getElementById('deck-list-container');
const btnPrev = document.getElementById('btn-prev');
const btnNext = document.getElementById('btn-next');

// Inicialização
async function iniciarApp() {
    try {
        const response = await fetch('deck.json');
        const data = await response.json();
        deck = data.questoes;
        
        gerarListaDireita();
        carregarQuestao(0);
    } catch (error) {
        questionText.innerText = "Erro ao carregar deck.json. Verifique o console.";
        console.error("Erro:", error);
    }
}

// Carrega uma questão na tela
function carregarQuestao(index) {
    questaoAtualIndex = index;
    questaoRespondida = false;
    const questao = deck[index];

    // Textos principais
    questionText.innerText = questao.pergunta;
    hintText.innerText = questao.dica;

    // Anotações
    noteInput.value = questao.anotacoes || "";
    noteInput.style.display = "none";
    noteMsg.style.display = "block";

    // Controle dos botões de navegação
    btnPrev.disabled = (index === 0);
    btnNext.disabled = (index === deck.length - 1);

    answersContainer.innerHTML = ""; 
    
    let todasRespostas = [questao.resposta_certa, ...questao.respostas_erradas];
    todasRespostas.sort(() => Math.random() - 0.5);

    todasRespostas.forEach(resposta => {
        const btn = document.createElement('button');
        btn.classList.add('answer-card');
        btn.innerHTML = `<span>${resposta}</span>`;
        
        btn.onclick = () => verificarResposta(btn, resposta, questao.resposta_certa);
        answersContainer.appendChild(btn);
    });
}

// Valida a resposta clicada
function verificarResposta(btnClicado, respostaEscolhida, respostaCerta) {
    if (questaoRespondida) return; 
    questaoRespondida = true;

    const botoes = document.querySelectorAll('.answer-card');
    
    botoes.forEach(btn => {
        const textoResposta = btn.innerText;
        btn.style.pointerEvents = 'none'; 
        
        if (textoResposta === respostaCerta) {
            btn.style.backgroundColor = '#4CAF50'; 
            btn.style.borderColor = '#4CAF50';
            btn.style.color = '#121212';
        } else if (btn === btnClicado) {
            btn.style.backgroundColor = '#F44336'; 
            btn.style.borderColor = '#F44336';
        }
    });

    noteMsg.style.display = "none";
    noteInput.style.display = "block";
}

// Salva a anotação em tempo real
noteInput.addEventListener('input', (e) => {
    deck[questaoAtualIndex].anotacoes = e.target.value;
});

// Ações dos botões de navegação
btnPrev.onclick = () => {
    if (questaoAtualIndex > 0) carregarQuestao(questaoAtualIndex - 1);
};

btnNext.onclick = () => {
    if (questaoAtualIndex < deck.length - 1) carregarQuestao(questaoAtualIndex + 1);
};

// Gera a lista direita
function gerarListaDireita() {
    deckListContainer.innerHTML = "";
    
    deck.forEach((q, index) => {
        const btn = document.createElement('button');
        btn.classList.add('outline-btn');
        btn.innerText = `Questão ${index + 1}: ${q.tema}`;
        btn.onclick = () => carregarQuestao(index);
        deckListContainer.appendChild(btn);
    });

    const btnDownload = document.createElement('button');
    btnDownload.classList.add('outline-btn');
    btnDownload.style.marginTop = '20px';
    btnDownload.style.borderColor = '#4CAF50';
    btnDownload.style.color = '#4CAF50';
    btnDownload.innerText = "💾 Baixar JSON Atualizado";
    btnDownload.onclick = baixarJSON;
    deckListContainer.appendChild(btnDownload);
}

function baixarJSON() {
    const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify({ questoes: deck }, null, 2));
    const downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", "deck_atualizado.json");
    document.body.appendChild(downloadAnchorNode);
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}

iniciarApp();