<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['front']) && isset($input['back'])) {
        $file = 'data.json';
        
        if (file_exists($file)) {
            $current_data = file_get_contents($file);
            $array_data = json_decode($current_data, true);
        } else {
            $array_data = [];
        }

        $array_data[] = [
            'front' => htmlspecialchars($input['front']),
            'back' => htmlspecialchars($input['back'])
        ];

        if (file_put_contents($file, json_encode($array_data, JSON_PRETTY_PRINT))) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
?>