<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $file = 'data.json';
    
    if (!file_exists($file)) {
        echo json_encode(['status' => 'error', 'message' => 'Arquivo não encontrado']);
        exit;
    }

    $current_data = json_decode(file_get_contents($file), true);
    $updated = false;

    foreach ($current_data as $item) {
        if ($item['id'] == $input['id']) {
            if (!isset($item['erros'])) $item['erros'] = 0;

            if ($input['acao'] === 'errou') {
                $item['erros']++;
            }

            // Lógica de Reclassificação Automática
            if ($item['erros'] == 0) {
                $item['dificuldade'] = 'Fácil';
            } elseif ($item['erros'] < 3) {
                $item['dificuldade'] = 'Média';
            } else {
                $item['dificuldade'] = 'Difícil';
            }

            $updated = true;
            break;
        }
    }

    if ($updated) {
        file_put_contents($file, json_encode($current_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID não encontrado']);
    }
}
?>