<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../services/FilterService.php';

header('Content-Type: application/json');

try {
    $service = new FilterService();
    $data = $service->listAll();
    echo json_encode(['ok' => true, 'message' => 'OK', 'data' => $data]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Server xətası', 'data' => []]);
}
