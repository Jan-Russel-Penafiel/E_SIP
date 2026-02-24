<?php
/**
 * Challenge Model
 * Manages coding challenges with difficulty, types, and validation.
 */

namespace Models;

use Core\Database;

class Challenge
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance('challenges');
    }

    /** Get all challenges */
    public function all(): array
    {
        return $this->db->findAll();
    }

    /** Find a challenge by ID */
    public function find(string $id): ?array
    {
        return $this->db->findById($id);
    }

    /** Get challenges by module */
    public function findByModule(string $moduleId): array
    {
        return $this->db->findBy('module_id', $moduleId);
    }

    /** Get challenges by language */
    public function findByLanguage(string $language): array
    {
        return $this->db->findBy('language', $language);
    }

    /** Get challenges by difficulty */
    public function findByDifficulty(string $difficulty): array
    {
        return $this->db->findBy('difficulty', $difficulty);
    }

    /**
     * Get challenges filtered by multiple criteria.
     * @param string|null $moduleId
     * @param string|null $difficulty
     * @param string|null $language
     * @return array
     */
    public function getFiltered(?string $moduleId = null, ?string $difficulty = null, ?string $language = null): array
    {
        $challenges = $this->all();

        if ($moduleId) {
            $challenges = array_filter($challenges, fn($c) => $c['module_id'] === $moduleId);
        }
        if ($difficulty) {
            $challenges = array_filter($challenges, fn($c) => $c['difficulty'] === $difficulty);
        }
        if ($language) {
            $challenges = array_filter($challenges, fn($c) => $c['language'] === $language);
        }

        return array_values($challenges);
    }

    /**
     * Validate a code submission against expected output.
     * Simulated execution for security — compares user output to expected.
     * @param string $challengeId
     * @param string $userCode     The code submitted by the user
     * @param string $userOutput   The output from in-browser execution
     * @return array Result with pass/fail, feedback, and XP
     */
    public function validateSubmission(string $challengeId, string $userCode, string $userOutput): array
    {
        $challenge = $this->find($challengeId);
        if (!$challenge) {
            return ['success' => false, 'message' => 'Challenge not found.', 'xp' => 0];
        }

        // For multiple choice challenges
        if ($challenge['type'] === 'multiple_choice') {
            $correct = trim($userOutput) === trim($challenge['correct_answer']);
            return [
                'success'  => $correct,
                'message'  => $correct ? 'Correct! Well done!' : 'Incorrect. Try again!',
                'xp'       => $correct ? $challenge['xp_reward'] : 0,
                'expected' => $challenge['correct_answer'],
            ];
        }

        // For code challenges — compare trimmed output
        $expectedOutput = trim($challenge['expected_output']);
        $actualOutput = trim($userOutput);
        $passed = $actualOutput === $expectedOutput;

        // Calculate XP with difficulty multiplier
        $config = require __DIR__ . '/../config/app.php';
        $difficultyKey = $challenge['difficulty'] ?? 'beginner';
        $multiplier = $config['difficulty'][$difficultyKey]['xp_multiplier'] ?? 1.0;
        $xpEarned = $passed ? (int)($challenge['xp_reward'] * $multiplier) : 0;

        return [
            'success'  => $passed,
            'message'  => $passed
                ? 'All tests passed! Great job!'
                : 'Output mismatch. Expected: "' . $expectedOutput . '", Got: "' . $actualOutput . '"',
            'xp'       => $xpEarned,
            'expected' => $expectedOutput,
            'actual'   => $actualOutput,
        ];
    }

    /** Count total challenges */
    public function count(): int
    {
        return $this->db->count();
    }

    /** Get a random challenge for the daily challenge */
    public function getRandomChallenge(?string $difficulty = null): ?array
    {
        $challenges = $difficulty ? $this->findByDifficulty($difficulty) : $this->all();
        if (empty($challenges)) return null;
        return $challenges[array_rand($challenges)];
    }
}
