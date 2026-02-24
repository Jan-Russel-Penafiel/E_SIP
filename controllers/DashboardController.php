<?php
/**
 * Dashboard Controller
 * Shows user dashboard with analytics, progress overview, and daily challenge.
 */

namespace Controllers;

use Models\User;
use Models\Module;
use Models\Challenge;
use Models\Progress;
use Models\Leaderboard;

class DashboardController extends BaseController
{
    private User $userModel;
    private Module $moduleModel;
    private Challenge $challengeModel;
    private Progress $progressModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel      = new User();
        $this->moduleModel    = new Module();
        $this->challengeModel = new Challenge();
        $this->progressModel  = new Progress();
    }

    /** Render main dashboard */
    public function index(): void
    {
        $this->auth->requireAuth();

        $user       = $this->auth->user();
        $userId     = $user['id'];
        $modules    = $this->moduleModel->all();
        $challenges = $this->challengeModel->all();
        $analytics  = $this->progressModel->getUserAnalytics($userId);
        $activity   = $this->progressModel->getDailyActivity($userId);
        $langDist   = $this->progressModel->getLanguageDistribution($userId, $challenges);

        // XP progress calculation
        $xpNeeded   = $this->userModel->xpForNextLevel($user);
        $xpPercent  = $xpNeeded > 0 ? min(100, round(($user['xp'] / $xpNeeded) * 100)) : 0;

        // Module progress
        $moduleProgress = [];
        foreach ($modules as $module) {
            $moduleProgress[$module['id']] = $this->moduleModel->calculateProgress(
                $module,
                $user['completed_challenges'] ?? [],
                $challenges
            );
        }

        // Daily challenge
        $dailyChallenge = $this->challengeModel->getRandomChallenge($user['difficulty'] ?? 'beginner');

        // Update leaderboard
        $leaderboard = new Leaderboard();
        $leaderboard->updateEntry(
            $userId,
            $user['username'],
            $user['level'],
            $user['xp'],
            count($user['completed_challenges'] ?? []),
            $user['streak'] ?? 0
        );
        $userRank = $leaderboard->getUserRank($userId);

        $this->render('dashboard/index', [
            'user'           => $user,
            'modules'        => $modules,
            'challenges'     => $challenges,
            'analytics'      => $analytics,
            'activity'       => $activity,
            'langDist'       => $langDist,
            'xpNeeded'       => $xpNeeded,
            'xpPercent'      => $xpPercent,
            'moduleProgress' => $moduleProgress,
            'dailyChallenge' => $dailyChallenge,
            'userRank'       => $userRank,
        ], 'Dashboard - E-SIP');
    }
}
