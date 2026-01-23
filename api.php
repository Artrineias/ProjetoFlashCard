<?php
header('Content-Type: application/json');

$dir = 'decks/';
if (!is_dir($dir)) mkdir($dir, 0777, true);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
    if ($action === 'list') {
        $files = glob($dir . '*.json');
        $decks = array_map(function($f) use ($dir) {
            return str_replace([$dir, '.json'], '', $f);
        }, $files);
        echo json_encode(array_values($decks));
        exit;
    }
    
    if ($action === 'get_deck') {
        $deckName = $_GET['name'] ?? '';
        $deckName = preg_replace('/[^a-z0-9_]/i', '', $deckName);
        $file = $dir . $deckName . '.json';
        
        if (file_exists($file)) {
            echo file_get_contents($file);
        } else {
            echo json_encode([]);
        }
        exit;
    }
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $act = $input['action'] ?? '';

    if ($act === 'create_deck') {
        $name = preg_replace('/[^a-z0-9_]/i', '', $input['name']);
        if (!$name) { echo json_encode(['status'=>'error']); exit; }
        
        $file = $dir . $name . '.json';
        if (!file_exists($file)) file_put_contents($file, json_encode([]));
        echo json_encode(['status' => 'success']);
    }

    if ($act === 'add_card') {
        $deckName = preg_replace('/[^a-z0-9_]/i', '', $input['deck']);
        $file = $dir . $deckName . '.json';
        
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($data)) $data = [];

        $data[] = [
            'id' => uniqid(), 
            'tema' => $input['tema'],
            'dificuldade' => 'Fácil',
            'pergunta' => $input['pergunta'],
            'resposta' => $input['resposta'],
            'erros' => 0,
            'acertos' => 0 
        ];

        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success']);
    }
    if ($act === 'update_status') {
        $deckName = preg_replace('/[^a-z0-9_]/i', '', $input['deck']);
        $file = $dir . $deckName . '.json';
        $targetId = $input['id'];
        $isError = $input['status'] === 'erro';

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            $found = false;
            foreach ($data as &$item) {
                if ($item['id'] == $targetId) {
                    
                    if (!isset($item['erros'])) $item['erros'] = 0;
                    if (!isset($item['acertos'])) $item['acertos'] = 0;

                    if ($isError) {
                        $item['erros']++;
                        if ($item['erros'] > 2) $item['dificuldade'] = 'Difícil';
                        else $item['dificuldade'] = 'Média';
                    } else {
                        $item['acertos']++;       
                        if ($item['erros'] > 0) $item['erros']--; // Diminui o erro
                        if ($item['erros'] == 0) $item['dificuldade'] = 'Fácil';
                    }
                    
                    $found = true;
                    break;
                }
            }

            if ($found) {
                file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID não encontrado']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Arquivo não existe']);
        }
    }
}
?>