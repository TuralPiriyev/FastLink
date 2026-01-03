<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../services/FilterService.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'message' => 'Method not allowed', 'data' => []]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $filterId = (int)($input['filter_id'] ?? 0);
    $name = trim($input['option_name'] ?? '');
    if ($filterId <= 0 || $name === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Filter və option tələb olunur', 'data' => []]);
        exit;
    }

    $service = new FilterService();
    $option = $service->createOption($filterId, $name);

    http_response_code(201);
    echo json_encode(['ok' => true, 'message' => 'Option yaradıldı', 'data' => $option]);
} catch (PDOException $e) {
    if ((int)($e->errorInfo[1] ?? 0) === 1062) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'Bu option artıq mövcuddur', 'data' => []]);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Server xətası', 'data' => []]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => $e->getMessage(), 'data' => []]);
}
