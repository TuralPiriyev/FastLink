<?php
require_once __DIR__ . '/../config/db.php';

class ProductService
{
    private PDO $pdo;
    private string $uploadDir;

    public function __construct()
    {
        $this->pdo = getPdo();
        $this->uploadDir = dirname(__DIR__) . '/public/uploads/products';
    }

    public function createProduct(int $userId, string $name, float $price, ?string $description, bool $isActive, array $files = []): int
    {
        $this->ensureUploadDir();

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('INSERT INTO products (user_id, name, price, description, is_active, created_at, updated_at) VALUES (:user_id, :name, :price, :description, :is_active, NOW(), NOW())');
            $stmt->execute([
                'user_id' => $userId,
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'is_active' => $isActive ? 1 : 0,
            ]);

            $productId = (int)$this->pdo->lastInsertId();

            $this->saveImages($productId, $files);

            $this->pdo->commit();
            return $productId;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function updateProduct(int $productId, int $userId, string $name, float $price, ?string $description, bool $isActive, array $files = []): void
    {
        $this->ensureUploadDir();

        $this->pdo->beginTransaction();
        try {
            $ownerStmt = $this->pdo->prepare('SELECT id FROM products WHERE id = :id AND user_id = :user_id LIMIT 1');
            $ownerStmt->execute(['id' => $productId, 'user_id' => $userId]);
            if (!$ownerStmt->fetch()) {
                throw new RuntimeException('Product not found or not owned by user');
            }

            $update = $this->pdo->prepare('UPDATE products SET name = :name, price = :price, description = :description, is_active = :is_active, updated_at = NOW() WHERE id = :id');
            $update->execute([
                'id' => $productId,
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'is_active' => $isActive ? 1 : 0,
            ]);

            if ($this->hasNewUploads($files)) {
                $this->deleteImages($productId);
                $this->saveImages($productId, $files);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function deleteProduct(int $productId, int $userId): void
    {
        $this->pdo->beginTransaction();
        try {
            $ownerStmt = $this->pdo->prepare('SELECT id FROM products WHERE id = :id AND user_id = :user_id LIMIT 1');
            $ownerStmt->execute(['id' => $productId, 'user_id' => $userId]);
            if (!$ownerStmt->fetch()) {
                throw new RuntimeException('Product not found or not owned by user');
            }

            $this->deleteImages($productId);

            $del = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
            $del->execute(['id' => $productId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function getProductsWithImages(int $userId): array
    {
        $sql = 'SELECT p.*, pi.image_path
                FROM products p
                LEFT JOIN product_images pi ON pi.product_id = p.id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC, pi.sort_order ASC, pi.id ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        $rows = $stmt->fetchAll();
        $grouped = [];

        foreach ($rows as $row) {
            $pid = (int)$row['id'];
            if (!isset($grouped[$pid])) {
                $grouped[$pid] = [
                    'id' => $pid,
                    'name' => $row['name'],
                    'price' => (float)$row['price'],
                    'description' => $row['description'],
                    'is_active' => (int)$row['is_active'] === 1,
                    'images' => [],
                    'created_at' => $row['created_at'],
                ];
            }
            if (!empty($row['image_path'])) {
                $grouped[$pid]['images'][] = $row['image_path'];
            }
        }

        // Ensure deterministic order
        return array_values($grouped);
    }

    private function saveImages(int $productId, array $files): void
    {
        if (empty($files['tmp_name']) || !is_array($files['tmp_name'])) {
            return;
        }

        $insert = $this->pdo->prepare('INSERT INTO product_images (product_id, image_path, sort_order, created_at) VALUES (:product_id, :image_path, :sort_order, NOW())');
        $sort = 0;

        foreach ($files['tmp_name'] as $idx => $tmp) {
            $error = $files['error'][$idx] ?? UPLOAD_ERR_NO_FILE;
            if ($error !== UPLOAD_ERR_OK) {
                continue;
            }

            $name = $files['name'][$idx] ?? 'upload';
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $safeExt = preg_match('/^[a-z0-9]+$/i', $ext) ? $ext : 'jpg';
            $fileName = uniqid('prd_', true) . '.' . $safeExt;
            $targetPath = $this->uploadDir . '/' . $fileName;

            if (!move_uploaded_file($tmp, $targetPath)) {
                continue;
            }

            // Store a web-accessible path (relative to project root)
            $dbPath = 'public/uploads/products/' . $fileName;
            $insert->execute([
                'product_id' => $productId,
                'image_path' => $dbPath,
                'sort_order' => $sort++,
            ]);
        }
    }

    private function ensureUploadDir(): void
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }
    }

    private function hasNewUploads(array $files): bool
    {
        if (empty($files['error']) || !is_array($files['error'])) {
            return false;
        }
        foreach ($files['error'] as $err) {
            if ($err === UPLOAD_ERR_OK) {
                return true;
            }
        }
        return false;
    }

    private function deleteImages(int $productId): void
    {
        $stmt = $this->pdo->prepare('SELECT image_path FROM product_images WHERE product_id = :id');
        $stmt->execute(['id' => $productId]);
        $paths = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        $root = dirname(__DIR__);
        foreach ($paths as $path) {
            $clean = ltrim((string)$path, '/');
            if (strpos($clean, 'public/') !== 0) {
                $clean = 'public/' . $clean;
            }
            $full = $root . '/' . $clean;
            if (is_file($full)) {
                @unlink($full);
            }
        }

        $del = $this->pdo->prepare('DELETE FROM product_images WHERE product_id = :id');
        $del->execute(['id' => $productId]);
    }
}
