<?php
/**
 * User Model
 * Encapsulates user data and operations: CRUD, XP management, level calculation.
 */

namespace Models;

use Core\Database;

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance('users');
    }

    /** Get all users */
    public function all(): array
    {
        return $this->db->findAll();
    }

    /** Find user by ID */
    public function find(string $id): ?array
    {
        return $this->db->findById($id);
    }

    /** Find user by email */
    public function findByEmail(string $email): ?array
    {
        $results = $this->db->findBy('email', $email);
        return $results[0] ?? null;
    }

    /** Find user by username */
    public function findByUsername(string $username): ?array
    {
        $results = $this->db->findBy('username', $username);
        return $results[0] ?? null;
    }

    /** Update user data */
    public function update(string $id, array $data): ?array
    {
        return $this->db->update($id, $data);
    }

    /**
     * Award XP to a user and handle level-up logic.
     * @param string $userId
     * @param int    $xp      Amount of XP to add
     * @return array Updated user with possible level change
     */
    public function awardXP(string $userId, int $xp): array
    {
        $user = $this->find($userId);
        if (!$user) {
            return [];
        }

        $config = require __DIR__ . '/../config/app.php';
        $multiplier = $config['gamification']['xp_level_multiplier'];

        $newXP = $user['xp'] + $xp;
        $newLevel = $user['level'];

        // Level-up calculation
        while ($newXP >= $newLevel * $multiplier && $newLevel < $config['gamification']['max_level']) {
            $newXP -= $newLevel * $multiplier;
            $newLevel++;
        }

        $updated = $this->update($userId, [
            'xp'    => $newXP,
            'level' => $newLevel,
        ]);

        return $updated ?? [];
    }

    /**
     * Add a badge to the user.
     * @param string $userId
     * @param string $badge Badge identifier
     */
    public function addBadge(string $userId, string $badge): ?array
    {
        $user = $this->find($userId);
        if (!$user) return null;

        $badges = $user['badges'] ?? [];
        if (!in_array($badge, $badges)) {
            $badges[] = $badge;
        }
        return $this->update($userId, ['badges' => $badges]);
    }

    /**
     * Mark a challenge as completed for a user.
     */
    public function completeChallenge(string $userId, string $challengeId): ?array
    {
        $user = $this->find($userId);
        if (!$user) return null;

        $completed = $user['completed_challenges'] ?? [];
        if (!in_array($challengeId, $completed)) {
            $completed[] = $challengeId;
        }
        return $this->update($userId, ['completed_challenges' => $completed]);
    }

    /**
     * Mark a module as completed for a user.
     */
    public function completeModule(string $userId, string $moduleId): ?array
    {
        $user = $this->find($userId);
        if (!$user) return null;

        $completed = $user['completed_modules'] ?? [];
        if (!in_array($moduleId, $completed)) {
            $completed[] = $moduleId;
        }
        return $this->update($userId, ['completed_modules' => $completed]);
    }

    /**
     * Update difficulty preference for adaptive learning.
     */
    public function setDifficulty(string $userId, string $difficulty): ?array
    {
        $validLevels = ['beginner', 'intermediate', 'advanced'];
        if (!in_array($difficulty, $validLevels)) return null;
        return $this->update($userId, ['difficulty' => $difficulty]);
    }

    /**
     * Calculate XP needed for next level.
     */
    public function xpForNextLevel(array $user): int
    {
        $config = require __DIR__ . '/../config/app.php';
        return $user['level'] * $config['gamification']['xp_level_multiplier'];
    }

    /**
     * Get top users by level and XP (for leaderboard).
     * @param int $limit
     * @return array
     */
    public function getTopUsers(int $limit = 10): array
    {
        $users = $this->all();
        usort($users, function ($a, $b) {
            if ($b['level'] !== $a['level']) {
                return $b['level'] - $a['level'];
            }
            return $b['xp'] - $a['xp'];
        });
        return array_slice($users, 0, $limit);
    }

    /** Increment user streak */
    public function incrementStreak(string $userId): ?array
    {
        $user = $this->find($userId);
        if (!$user) return null;
        return $this->update($userId, ['streak' => ($user['streak'] ?? 0) + 1]);
    }

    /** Reset user streak */
    public function resetStreak(string $userId): ?array
    {
        return $this->update($userId, ['streak' => 0]);
    }
}
