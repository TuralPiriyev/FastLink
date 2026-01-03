<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../services/FilterService.php';

header('Content-Type: application/json');

try {
    $userId = (int)($_SESSION['user_id'] ?? 0);
    if ($userId <= 0) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'message' => 'Auth required', 'data' => []]);
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);
    $filterIds = isset($payload['filter_ids']) && is_array($payload['filter_ids']) ? array_map('intval', $payload['filter_ids']) : [];

    $service = new FilterService();
    $service->saveUserFilters($userId, $filterIds);
    $ids = $service->getUserFilterIds($userId);

    echo json_encode(['ok' => true, 'message' => 'Saved', 'data' => ['filter_ids' => $ids]]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Server error', 'data' => []]);
}
