<?php
/**
 * Leaderboard Controller
 * Displays global rankings and user positions.
 */

namespace Controllers;

use Models\Leaderboard;

class LeaderboardController extends BaseController
{
    private Leaderboard $leaderboardModel;

    public function __construct()
    {
        parent::__construct();
        $this->leaderboardModel = new Leaderboard();
    }

    /** Show the leaderboard page */
    public function index(): void
    {
        $this->auth->requireAuth();

        $topPlayers = $this->leaderboardModel->getTopPlayers(20);
        $user       = $this->auth->user();
        $userRank   = $this->leaderboardModel->getUserRank($user['id']);

        $this->render('leaderboard/index', [
            'topPlayers' => $topPlayers,
            'user'       => $user,
            'userRank'   => $userRank,
        ], 'Leaderboard - E-SIP');
    }
}
