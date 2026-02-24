<?php
/**
 * Module Model
 * Manages learning modules and their lessons.
 */

namespace Models;

use Core\Database;

class Module
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance('modules');
    }

    /** Get all modules ordered by sort position */
    public function all(): array
    {
        $modules = $this->db->findAll();
        usort($modules, fn($a, $b) => ($a['order'] ?? 0) - ($b['order'] ?? 0));
        return $modules;
    }

    /** Find module by ID */
    public function find(string $id): ?array
    {
        return $this->db->findById($id);
    }

    /** Get modules by language */
    public function findByLanguage(string $language): array
    {
        return $this->db->findBy('language', $language);
    }

    /** Get modules by difficulty */
    public function findByDifficulty(string $difficulty): array
    {
        return $this->db->findBy('difficulty', $difficulty);
    }

    /**
     * Get modules filtered and sorted.
     * @param string|null $language   Filter by language
     * @param string|null $difficulty Filter by difficulty
     * @return array
     */
    public function getFiltered(?string $language = null, ?string $difficulty = null): array
    {
        $modules = $this->all();

        if ($language) {
            $modules = array_filter($modules, fn($m) => $m['language'] === $language);
        }
        if ($difficulty) {
            $modules = array_filter($modules, fn($m) => $m['difficulty'] === $difficulty);
        }

        return array_values($modules);
    }

    /** Get available languages from modules */
    public function getLanguages(): array
    {
        $modules = $this->all();
        return array_unique(array_column($modules, 'language'));
    }

    /** Count total modules */
    public function count(): int
    {
        return $this->db->count();
    }

    /**
     * Calculate user progress for a module.
     * @param array $module     Module data
     * @param array $completed  Array of completed challenge IDs
     * @param array $challenges All challenges for this module
     * @return float Completion percentage (0-100)
     */
    public function calculateProgress(array $module, array $completed, array $challenges): float
    {
        $moduleChallenges = array_filter($challenges, fn($c) => $c['module_id'] === $module['id']);
        $total = count($moduleChallenges);
        if ($total === 0) return 0;

        $done = count(array_filter($moduleChallenges, fn($c) => in_array($c['id'], $completed)));
        return round(($done / $total) * 100, 1);
    }
}
