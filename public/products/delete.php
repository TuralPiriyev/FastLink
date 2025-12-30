<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../helpers/redirect.php';
require_once __DIR__ . '/../../services/ProductService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/index.php?page=products');
}

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    redirect('/public/auth/auth-login.php');
}

$productId = (int)($_POST['product_id'] ?? 0);

if ($productId <= 0) {
    $_SESSION['errors'] = ['general' => 'Məhsul tapılmadı.'];
    redirect('/index.php?page=products');
}

try {
    $service = new ProductService();
    $service->deleteProduct($productId, $userId);
    $_SESSION['success'] = 'Məhsul silindi.';
} catch (Throwable $e) {
    $_SESSION['errors'] = ['general' => 'Məhsul silinərkən xəta baş verdi.'];
}

redirect('/index.php?page=products');
