<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../helpers/validation.php';
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
$name = trim($_POST['name'] ?? '');
$priceRaw = $_POST['price'] ?? '0';
$description = trim($_POST['description'] ?? '');
$isActive = isset($_POST['is_active']) && $_POST['is_active'] == '1';

$errors = [];

if ($productId <= 0) {
    $errors['general'] = 'Məhsul tapılmadı.';
}

if (!isRequired($name)) {
    $errors['name'] = 'Məhsul adı tələb olunur.';
} elseif (mb_strlen($name) > 150) {
    $errors['name'] = 'Məhsul adı 150 simvoldan çox ola bilməz.';
}

if (!is_numeric($priceRaw) || (float)$priceRaw < 0) {
    $errors['price'] = 'Qiymət düzgün deyil.';
} else {
    $price = round((float)$priceRaw, 2);
}

if (isset($_FILES['images']) && is_array($_FILES['images']['error'])) {
    foreach ($_FILES['images']['error'] as $idx => $err) {
        if ($err === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($err !== UPLOAD_ERR_OK) {
            $errors['images'] = 'Şəkil yüklənə bilmədi.';
            break;
        }
        $tmp = $_FILES['images']['tmp_name'][$idx] ?? '';
        $mime = $tmp ? mime_content_type($tmp) : '';
        if ($mime && strpos($mime, 'image/') !== 0) {
            $errors['images'] = 'Yalnız şəkil faylı yükləyə bilərsiniz.';
            break;
        }
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = [
        'name' => $name,
        'price' => $priceRaw,
        'description' => $description,
        'is_active' => $isActive ? 1 : 0,
    ];
    redirect('/index.php?page=products');
}

try {
    $service = new ProductService();
    $service->updateProduct($productId, $userId, $name, $price, $description ?: null, $isActive, $_FILES['images'] ?? []);
    $_SESSION['success'] = 'Məhsul yeniləndi.';
} catch (Throwable $e) {
    $_SESSION['errors'] = ['general' => 'Məhsul yenilənərkən xəta baş verdi.'];
}

redirect('/index.php?page=products');
