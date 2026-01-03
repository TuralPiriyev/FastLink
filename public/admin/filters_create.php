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
    $name = trim($input['filter_name'] ?? '');
    if ($name === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Filter adı tələb olunur', 'data' => []]);
        exit;
    }

    $service = new FilterService();
    $filter = $service->createFilter($name);

    http_response_code(201);
    echo json_encode(['ok' => true, 'message' => 'Filter yaradıldı', 'data' => $filter]);
} catch (PDOException $e) {
    if ((int)($e->errorInfo[1] ?? 0) === 1062) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'Bu adla filter artıq var', 'data' => []]);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Server xətası', 'data' => []]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => $e->getMessage(), 'data' => []]);
}
