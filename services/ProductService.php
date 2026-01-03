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

    public function createProduct(int $userId, string $name, float $price, ?string $description, bool $isActive, array $files = [], array $filterOptionIds = []): int
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
            $this->syncFilterOptions($productId, $filterOptionIds);

            $this->pdo->commit();
            return $productId;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function updateProduct(int $productId, int $userId, string $name, float $price, ?string $description, bool $isActive, array $files = [], array $filterOptionIds = []): void
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

            $this->syncFilterOptions($productId, $filterOptionIds);

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
        $sql = 'SELECT p.*, pi.image_path,
                   fo.id AS option_id, fo.name AS option_name, fo.slug AS option_slug,
                   f.id AS filter_id, f.name AS filter_name, f.slug AS filter_slug
                FROM products p
                LEFT JOIN product_images pi ON pi.product_id = p.id
                LEFT JOIN product_filter_options pfo ON pfo.product_id = p.id
                LEFT JOIN filter_options fo ON fo.id = pfo.filter_option_id
                LEFT JOIN filters f ON f.id = fo.filter_id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC, pi.sort_order ASC, pi.id ASC, f.name ASC, fo.name ASC';

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
                    'filters' => [],
                ];
            }

            if (!empty($row['image_path'])) {
                $grouped[$pid]['images'][] = $row['image_path'];
            }

            if (!empty($row['option_id']) && !empty($row['filter_id'])) {
                $fid = (int)$row['filter_id'];
                $oid = (int)$row['option_id'];
                if (!isset($grouped[$pid]['filters'][$fid])) {
                    $grouped[$pid]['filters'][$fid] = [
                        'filter_id' => $fid,
                        'filter_slug' => $row['filter_slug'],
                        'filter_name' => $row['filter_name'],
                        'options' => [],
                    ];
                }
                if (!isset($grouped[$pid]['filters'][$fid]['options'][$oid])) {
                    $grouped[$pid]['filters'][$fid]['options'][$oid] = [
                        'id' => $oid,
                        'slug' => $row['option_slug'],
                        'name' => $row['option_name'],
                    ];
                }
            }
        }

        // Normalize options to indexed arrays for output
        foreach ($grouped as &$product) {
            if (!empty($product['filters'])) {
                foreach ($product['filters'] as &$f) {
                    $f['options'] = array_values($f['options']);
                }
                unset($f);
                $product['filters'] = array_values($product['filters']);
            }
        }
        unset($product);

        return array_values($grouped);
    }

    public function getSelectedFilterOptionIds(int $productId): array
    {
        $stmt = $this->pdo->prepare('SELECT filter_option_id FROM product_filter_options WHERE product_id = :pid');
        $stmt->execute(['pid' => $productId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
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

    private function syncFilterOptions(int $productId, array $filterOptionIds): void
    {
        $del = $this->pdo->prepare('DELETE FROM product_filter_options WHERE product_id = :pid');
        $del->execute(['pid' => $productId]);

        if (empty($filterOptionIds)) {
            return;
        }

        $insert = $this->pdo->prepare('INSERT INTO product_filter_options (product_id, filter_option_id) VALUES (:pid, :oid)');
        foreach ($filterOptionIds as $oid) {
            $insert->execute([
                'pid' => $productId,
                'oid' => (int)$oid,
            ]);
        }
    }

    /**
     * Attach given filter options to many products (additive, keeps existing).
     */
    public function attachOptionsToProducts(int $userId, array $productIds, array $optionIds): void
    {
        $productIds = array_values(array_unique(array_map('intval', $productIds)));
        $optionIds = array_values(array_unique(array_map('intval', $optionIds)));
        if (empty($productIds) || empty($optionIds)) {
            return;
        }

        // only user's products
        $in = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $this->pdo->prepare("SELECT id FROM products WHERE user_id = ? AND id IN ($in)");
        $stmt->execute(array_merge([$userId], $productIds));
        $ownedIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
        if (empty($ownedIds)) {
            return;
        }

        $this->pdo->beginTransaction();
        try {
            $insert = $this->pdo->prepare('INSERT IGNORE INTO product_filter_options (product_id, filter_option_id) VALUES (:pid, :oid)');
            foreach ($ownedIds as $pid) {
                foreach ($optionIds as $oid) {
                    $insert->execute(['pid' => $pid, 'oid' => $oid]);
                }
            }
            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
