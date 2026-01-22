<?php
header('Content-Type: application/json');

$file = 'data.json';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Carrega ou inicia o array
    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    if (!is_array($data)) $data = [];

    // --- AÇÃO: CRIAR NOVO CARD ---
    if (isset($input['action']) && $input['action'] === 'create') {
        // Gera ID simples baseado no tempo para garantir unicidade
        $newId = time(); 
        
        $data[] = [
            'id' => $newId,
            'tema' => htmlspecialchars($input['tema']),
            'dificuldade' => 'Fácil', // Começa fácil
            'pergunta' => $input['pergunta'], // Sem htmlspecialchars aqui para URL funcionar melhor
            'resposta' => $input['resposta'],
            'erros' => 0
        ];
    }

    // --- AÇÃO: ATUALIZAR ERROS ---
    if (isset($input['action']) && $input['action'] === 'update') {
        $targetId = $input['id'];
        $isError = $input['status'] === 'erro';

        // Loop tradicional sem referência (&) para evitar o erro que você mencionou
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['id'] == $targetId) {
                if (!isset($data[$i]['erros'])) $data[$i]['erros'] = 0;

                if ($isError) {
                    $data[$i]['erros']++; // Aumenta erro
                } else {
                    // Se acertou, podemos diminuir o erro ou manter (opcional: aqui diminui)
                    if ($data[$i]['erros'] > 0) $data[$i]['erros']--;
                }

                // Recalcula dificuldade
                if ($data[$i]['erros'] == 0) $data[$i]['dificuldade'] = 'Fácil';
                elseif ($data[$i]['erros'] < 3) $data[$i]['dificuldade'] = 'Média';
                else $data[$i]['dificuldade'] = 'Difícil';
                
                break;
            }
        }
    }

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['status' => 'success']);
}
?>