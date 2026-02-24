<?php
/**
 * Leaderboard Model
 * Manages global leaderboard rankings and peer comparisons.
 */

namespace Models;

use Core\Database;

class Leaderboard
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance('leaderboard');
    }

    /**
     * Update or create a leaderboard entry for a user.
     * @param string $userId
     * @param string $username
     * @param int    $level
     * @param int    $xp
     * @param int    $challengesCompleted
     * @param int    $streak
     * @return array
     */
    public function updateEntry(
        string $userId,
        string $username,
        int    $level,
        int    $xp,
        int    $challengesCompleted,
        int    $streak
    ): array {
        $existing = $this->db->findBy('user_id', $userId);

        $data = [
            'user_id'              => $userId,
            'username'             => $username,
            'level'                => $level,
            'xp'                   => $xp,
            'challenges_completed' => $challengesCompleted,
            'streak'               => $streak,
            'score'                => ($level * 1000) + $xp + ($challengesCompleted * 50) + ($streak * 10),
        ];

        if (!empty($existing)) {
            return $this->db->update($existing[0]['id'], $data);
        }
        return $this->db->insert($data);
    }

    /**
     * Get top players sorted by score.
     * @param int $limit
     * @return array Ranked entries
     */
    public function getTopPlayers(int $limit = 20): array
    {
        $entries = $this->db->findAll();
        usort($entries, fn($a, $b) => ($b['score'] ?? 0) - ($a['score'] ?? 0));

        // Add rank
        $ranked = [];
        foreach (array_slice($entries, 0, $limit) as $i => $entry) {
            $entry['rank'] = $i + 1;
            $ranked[] = $entry;
        }

        return $ranked;
    }

    /**
     * Get a user's rank.
     * @param string $userId
     * @return int Rank (1-based) or 0 if not found
     */
    public function getUserRank(string $userId): int
    {
        $entries = $this->db->findAll();
        usort($entries, fn($a, $b) => ($b['score'] ?? 0) - ($a['score'] ?? 0));

        foreach ($entries as $i => $entry) {
            if ($entry['user_id'] === $userId) {
                return $i + 1;
            }
        }
        return 0;
    }
}
