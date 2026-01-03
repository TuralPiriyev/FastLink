<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../services/ProductService.php';

header('Content-Type: application/json');

try {
    $userId = (int)($_SESSION['user_id'] ?? 0);
    if ($userId <= 0) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'message' => 'Auth required', 'data' => []]);
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);
    $productIds = isset($payload['product_ids']) && is_array($payload['product_ids']) ? array_map('intval', $payload['product_ids']) : [];
    $optionIds = isset($payload['filter_option_ids']) && is_array($payload['filter_option_ids']) ? array_map('intval', $payload['filter_option_ids']) : [];

    if (empty($productIds) || empty($optionIds)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Product və option seçilməlidir', 'data' => []]);
        exit;
    }

    $service = new ProductService();
    $service->attachOptionsToProducts($userId, $productIds, $optionIds);

    echo json_encode(['ok' => true, 'message' => 'Əlavə edildi', 'data' => []]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Server xətası', 'data' => []]);
}
