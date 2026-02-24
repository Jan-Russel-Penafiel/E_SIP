<?php
/**
 * Progress Model
 * Tracks and records user learning progress, submissions, and analytics.
 */

namespace Models;

use Core\Database;

class Progress
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance('progress');
    }

    /**
     * Record a challenge attempt.
     * @param string $userId
     * @param string $challengeId
     * @param bool   $success
     * @param int    $xpEarned
     * @param int    $timeTaken   Time in seconds
     * @param string $code        Submitted code
     * @return array The progress record
     */
    public function recordAttempt(
        string $userId,
        string $challengeId,
        bool   $success,
        int    $xpEarned,
        int    $timeTaken,
        string $code
    ): array {
        return $this->db->insert([
            'user_id'      => $userId,
            'challenge_id' => $challengeId,
            'success'      => $success,
            'xp_earned'    => $xpEarned,
            'time_taken'   => $timeTaken,
            'code'         => $code,
            'attempted_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /** Get all attempts by a user */
    public function findByUser(string $userId): array
    {
        return $this->db->findBy('user_id', $userId);
    }

    /** Get attempts for a specific challenge by a user */
    public function findByUserAndChallenge(string $userId, string $challengeId): array
    {
        $all = $this->findByUser($userId);
        return array_values(array_filter($all, fn($p) => $p['challenge_id'] === $challengeId));
    }

    /**
     * Get analytics for a user.
     * @param string $userId
     * @return array Analytics data: totalAttempts, successRate, totalXP, averageTime
     */
    public function getUserAnalytics(string $userId): array
    {
        $attempts = $this->findByUser($userId);
        $total = count($attempts);

        if ($total === 0) {
            return [
                'total_attempts' => 0,
                'successful'     => 0,
                'success_rate'   => 0,
                'total_xp'       => 0,
                'average_time'   => 0,
                'recent'         => [],
            ];
        }

        $successful = count(array_filter($attempts, fn($a) => $a['success']));
        $totalXP    = array_sum(array_column($attempts, 'xp_earned'));
        $avgTime    = array_sum(array_column($attempts, 'time_taken')) / $total;

        // Get 10 most recent attempts
        usort($attempts, fn($a, $b) => strtotime($b['attempted_at']) - strtotime($a['attempted_at']));
        $recent = array_slice($attempts, 0, 10);

        return [
            'total_attempts' => $total,
            'successful'     => $successful,
            'success_rate'   => round(($successful / $total) * 100, 1),
            'total_xp'       => $totalXP,
            'average_time'   => round($avgTime),
            'recent'         => $recent,
        ];
    }

    /**
     * Get daily activity data for charts (last 7 days).
     * @param string $userId
     * @return array
     */
    public function getDailyActivity(string $userId): array
    {
        $attempts = $this->findByUser($userId);
        $days = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dayAttempts = array_filter($attempts, function ($a) use ($date) {
                return str_starts_with($a['attempted_at'], $date);
            });
            $days[] = [
                'date'     => $date,
                'label'    => date('D', strtotime($date)),
                'attempts' => count($dayAttempts),
                'xp'       => array_sum(array_column($dayAttempts, 'xp_earned')),
            ];
        }

        return $days;
    }

    /**
     * Get language distribution for a user.
     * @param string $userId
     * @param array  $challenges All challenges (for language lookup)
     * @return array Language => count mapping
     */
    public function getLanguageDistribution(string $userId, array $challenges): array
    {
        $attempts = $this->findByUser($userId);
        $distribution = [];

        $challengeMap = [];
        foreach ($challenges as $c) {
            $challengeMap[$c['id']] = $c['language'];
        }

        foreach ($attempts as $attempt) {
            $lang = $challengeMap[$attempt['challenge_id']] ?? 'unknown';
            $distribution[$lang] = ($distribution[$lang] ?? 0) + 1;
        }

        arsort($distribution);
        return $distribution;
    }
}
