<?php
/**
 * Profile Controller
 * Handles user profile display and settings.
 */

namespace Controllers;

use Models\User;
use Models\Progress;
use Models\Challenge;

class ProfileController extends BaseController
{
    private User $userModel;
    private Progress $progressModel;
    private Challenge $challengeModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel      = new User();
        $this->progressModel  = new Progress();
        $this->challengeModel = new Challenge();
    }

    /** Show user profile */
    public function index(): void
    {
        $this->auth->requireAuth();

        $user       = $this->auth->user();
        $analytics  = $this->progressModel->getUserAnalytics($user['id']);
        $challenges = $this->challengeModel->all();
        $langDist   = $this->progressModel->getLanguageDistribution($user['id'], $challenges);
        $xpNeeded   = $this->userModel->xpForNextLevel($user);
        $xpPercent  = $xpNeeded > 0 ? min(100, round(($user['xp'] / $xpNeeded) * 100)) : 0;

        // Badge definitions
        $badgeInfo = [
            'first_challenge'  => ['label' => 'First Step',      'icon' => '🎯', 'desc' => 'Complete your first challenge'],
            'five_challenges'  => ['label' => 'Getting Started',  'icon' => '⭐', 'desc' => 'Complete 5 challenges'],
            'ten_challenges'   => ['label' => 'Dedicated',        'icon' => '🏅', 'desc' => 'Complete 10 challenges'],
            'quarter_century'  => ['label' => 'Quarter Century',  'icon' => '🏆', 'desc' => 'Complete 25 challenges'],
            'streak_3'         => ['label' => 'On Fire',          'icon' => '🔥', 'desc' => '3-day streak'],
            'streak_7'         => ['label' => 'Unstoppable',      'icon' => '💪', 'desc' => '7-day streak'],
            'level_5'          => ['label' => 'Rising Star',      'icon' => '🌟', 'desc' => 'Reach level 5'],
            'level_10'         => ['label' => 'Code Master',      'icon' => '👑', 'desc' => 'Reach level 10'],
        ];

        $this->render('profile/index', [
            'user'       => $user,
            'analytics'  => $analytics,
            'langDist'   => $langDist,
            'xpNeeded'   => $xpNeeded,
            'xpPercent'  => $xpPercent,
            'badgeInfo'  => $badgeInfo,
            'langConfig' => $this->config['languages'],
        ], 'Profile - E-SIP');
    }

    /** Update user settings */
    public function updateSettings(): void
    {
        $this->auth->requireAuth();

        $user       = $this->auth->user();
        $difficulty = $this->input('difficulty', 'beginner');

        $this->userModel->setDifficulty($user['id'], $difficulty);
        $this->redirectWithMessage('/profile', 'Settings updated successfully.', 'success');
    }
}
