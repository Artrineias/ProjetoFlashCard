<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $file = 'data.json';
    
    // Carrega dados existentes
    $current_data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    if (!is_array($current_data)) $current_data = [];

    // Encontra o maior ID atual para continuar a contagem
    $max_id = 0;
    foreach ($current_data as $item) {
        if (isset($item['id']) && $item['id'] > $max_id) {
            $max_id = $item['id'];
        }
    }

    // Normaliza entrada (se for um único objeto, transforma em array)
    $new_items = [];
    if (isset($input['pergunta'])) {
        $new_items[] = $input; // É um único card
    } elseif (is_array($input)) {
        $new_items = $input; // É uma lista (importação em massa)
    }

    // Processa os novos itens
    foreach ($new_items as $item) {
        if (isset($item['pergunta']) && isset($item['resposta'])) {
            $max_id++; // Incrementa ID
            
            $current_data[] = [
                'id' => isset($item['id']) ? intval($item['id']) : $max_id, // Usa ID do usuário se existir, senão usa o automático
                'tema' => htmlspecialchars($item['tema'] ?? 'Geral'),
                'dificuldade' => htmlspecialchars($item['dificuldade'] ?? 'Média'),
                'pergunta' => htmlspecialchars($item['pergunta']),
                'resposta' => htmlspecialchars($item['resposta'])
            ];
        }
    }

    if (file_put_contents($file, json_encode($current_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>