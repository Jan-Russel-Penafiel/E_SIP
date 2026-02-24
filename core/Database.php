<?php
/**
 * Database Singleton - JSON File Storage Engine
 * Handles all read/write operations to JSON data files.
 * Implements Singleton pattern to ensure single instance per data file.
 */

namespace Core;

class Database
{
    /** @var Database[] Singleton instances keyed by collection name */
    private static array $instances = [];

    /** @var string Full path to the JSON file */
    private string $filePath;

    /** @var array In-memory data cache */
    private array $data = [];

    /**
     * Private constructor enforces Singleton usage.
     * @param string $collection Name of the JSON collection (without .json)
     */
    private function __construct(string $collection)
    {
        $config = require __DIR__ . '/../config/app.php';
        $dataDir = $config['data_dir'];

        // Ensure data directory exists
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }

        $this->filePath = $dataDir . '/' . $collection . '.json';

        // Create file if it doesn't exist
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }

        $this->load();
    }

    /** Prevent cloning of Singleton */
    private function __clone() {}

    /**
     * Get Singleton instance for a specific collection.
     * @param string $collection JSON collection name
     * @return Database
     */
    public static function getInstance(string $collection): self
    {
        if (!isset(self::$instances[$collection])) {
            self::$instances[$collection] = new self($collection);
        }
        return self::$instances[$collection];
    }

    /**
     * Load data from JSON file into memory.
     */
    private function load(): void
    {
        $content = file_get_contents($this->filePath);
        // Strip UTF-8 BOM if present
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }
        $this->data = json_decode($content, true) ?? [];
    }

    /**
     * Persist in-memory data to JSON file.
     * @return bool
     */
    private function save(): bool
    {
        $json = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($this->filePath, $json) !== false;
    }

    /**
     * Get all records from the collection.
     * @return array
     */
    public function findAll(): array
    {
        $this->load(); // Refresh from file
        return $this->data;
    }

    /**
     * Find a single record by ID.
     * @param string $id
     * @return array|null
     */
    public function findById(string $id): ?array
    {
        $this->load();
        foreach ($this->data as $record) {
            if (isset($record['id']) && $record['id'] === $id) {
                return $record;
            }
        }
        return null;
    }

    /**
     * Find records matching a key-value condition.
     * @param string $key
     * @param mixed  $value
     * @return array
     */
    public function findBy(string $key, mixed $value): array
    {
        $this->load();
        return array_values(array_filter($this->data, function ($record) use ($key, $value) {
            return isset($record[$key]) && $record[$key] === $value;
        }));
    }

    /**
     * Insert a new record. Auto-generates ID if not provided.
     * @param array $record
     * @return array The inserted record
     */
    public function insert(array $record): array
    {
        $this->load();
        if (!isset($record['id'])) {
            $record['id'] = $this->generateId();
        }
        $record['created_at'] = date('Y-m-d H:i:s');
        $record['updated_at'] = date('Y-m-d H:i:s');
        $this->data[] = $record;
        $this->save();
        return $record;
    }

    /**
     * Update a record by ID with given data.
     * @param string $id
     * @param array  $updates
     * @return array|null Updated record or null
     */
    public function update(string $id, array $updates): ?array
    {
        $this->load();
        foreach ($this->data as &$record) {
            if (isset($record['id']) && $record['id'] === $id) {
                $updates['updated_at'] = date('Y-m-d H:i:s');
                $record = array_merge($record, $updates);
                $this->save();
                return $record;
            }
        }
        return null;
    }

    /**
     * Delete a record by ID.
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $this->load();
        $originalCount = count($this->data);
        $this->data = array_values(array_filter($this->data, function ($record) use ($id) {
            return !isset($record['id']) || $record['id'] !== $id;
        }));
        if (count($this->data) < $originalCount) {
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Count total records in collection.
     * @return int
     */
    public function count(): int
    {
        $this->load();
        return count($this->data);
    }

    /**
     * Generate a unique ID using uniqid + random bytes.
     * @return string
     */
    private function generateId(): string
    {
        return bin2hex(random_bytes(8));
    }

    /**
     * Reset Singleton instances (for testing).
     */
    public static function resetInstances(): void
    {
        self::$instances = [];
    }
}
