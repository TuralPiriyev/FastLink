<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/slug.php';

class FilterService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPdo();
    }

    public function createFilter(string $name): array
    {
        $name = trim($name);
        if ($name === '' || mb_strlen($name) > 191) {
            throw new InvalidArgumentException('Filter adı boş ola bilməz və 191 simvolu keçməməlidir.');
        }
        $slug = slugify($name);

        $stmt = $this->pdo->prepare('INSERT INTO filters (name, slug, created_at, updated_at) VALUES (:name, :slug, NOW(), NOW())');
        $stmt->execute(['name' => $name, 'slug' => $slug]);

        return ['id' => (int)$this->pdo->lastInsertId(), 'name' => $name, 'slug' => $slug];
    }

    public function createOption(int $filterId, string $name, int $sortOrder = 0): array
    {
        $name = trim($name);
        if ($filterId <= 0) {
            throw new InvalidArgumentException('Filter seçilməyib.');
        }
        if ($name === '' || mb_strlen($name) > 191) {
            throw new InvalidArgumentException('Option adı boş ola bilməz və 191 simvolu keçməməlidir.');
        }
        $slug = slugify($name);

        $stmt = $this->pdo->prepare('INSERT INTO filter_options (filter_id, name, slug, sort_order, created_at, updated_at) VALUES (:fid, :name, :slug, :ord, NOW(), NOW())');
        $stmt->execute(['fid' => $filterId, 'name' => $name, 'slug' => $slug, 'ord' => $sortOrder]);

        return [
            'id' => (int)$this->pdo->lastInsertId(),
            'filter_id' => $filterId,
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $sortOrder,
        ];
    }

    public function listAll(): array
    {
        $filters = $this->pdo->query('SELECT * FROM filters ORDER BY name')->fetchAll();
        $options = $this->pdo->query('SELECT * FROM filter_options ORDER BY filter_id, sort_order, name')->fetchAll();

        $byId = [];
        foreach ($filters as $f) {
            $byId[$f['id']] = $f + ['options' => []];
        }
        foreach ($options as $o) {
            if (isset($byId[$o['filter_id']])) {
                $byId[$o['filter_id']]['options'][] = $o;
            }
        }
        return array_values($byId);
    }

    public function getSelectedOptionIds(int $productId): array
    {
        $stmt = $this->pdo->prepare('SELECT filter_option_id FROM product_filter_options WHERE product_id = :pid');
        $stmt->execute(['pid' => $productId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
    }

    public function getUserFilterIds(int $userId): array
    {
        $this->ensureUserFiltersTable();
        $stmt = $this->pdo->prepare('SELECT filter_id FROM user_filters WHERE user_id = :uid');
        $stmt->execute(['uid' => $userId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
    }

    public function saveUserFilters(int $userId, array $filterIds): void
    {
        $this->ensureUserFiltersTable();
        $this->pdo->beginTransaction();
        try {
            $del = $this->pdo->prepare('DELETE FROM user_filters WHERE user_id = :uid');
            $del->execute(['uid' => $userId]);
            if (!empty($filterIds)) {
                $ins = $this->pdo->prepare('INSERT INTO user_filters (user_id, filter_id) VALUES (:uid, :fid)');
                foreach ($filterIds as $fid) {
                    $ins->execute(['uid' => $userId, 'fid' => (int)$fid]);
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

    private function ensureUserFiltersTable(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS user_filters (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            filter_id BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_user_filter (user_id, filter_id),
            KEY idx_filter (filter_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
        $this->pdo->exec($sql);
    }
}
