<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../services/ProductService.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'message' => 'Method not allowed', 'data' => []]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $name = trim($input['name'] ?? '');
    $price = $input['price'] ?? null;
    $description = trim($input['description'] ?? '');
    $isActive = (bool)($input['is_active'] ?? true);
    $filterOptionIds = array_map('intval', $input['filter_option_ids'] ?? []);
    $userId = (int)($_SESSION['user_id'] ?? 0);

    if ($name === '' || $price === null || !is_numeric($price)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Ad və qiymət tələb olunur', 'data' => []]);
        exit;
    }

    $service = new ProductService();
    $productId = $service->createProduct($userId, $name, (float)$price, $description ?: null, $isActive, [], $filterOptionIds);

    http_response_code(201);
    echo json_encode(['ok' => true, 'message' => 'Məhsul yaradıldı', 'data' => ['id' => $productId]]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Server xətası', 'data' => []]);
}
