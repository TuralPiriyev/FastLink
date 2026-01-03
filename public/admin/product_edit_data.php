<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../services/ProductService.php';
require_once __DIR__ . '/../../services/FilterService.php';

header('Content-Type: application/json');

try {
    $productId = (int)($_GET['id'] ?? 0);
    if ($productId <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Product id tələb olunur', 'data' => []]);
        exit;
    }

    $productService = new ProductService();
    $filterService = new FilterService();

    $productStmt = getPdo()->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
    $productStmt->execute(['id' => $productId]);
    $product = $productStmt->fetch();
    if (!$product) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'message' => 'Product tapılmadı', 'data' => []]);
        exit;
    }

    $selected = $productService->getSelectedFilterOptionIds($productId);
    $filters = $filterService->listAll();

    echo json_encode(['ok' => true, 'message' => 'OK', 'data' => [
        'product' => $product,
        'selected_option_ids' => $selected,
        'filters' => $filters,
    ]]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Server xətası', 'data' => []]);
}
