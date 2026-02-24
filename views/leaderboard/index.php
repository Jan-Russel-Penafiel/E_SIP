<!-- Leaderboard View -->
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-1 text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">Leaderboard</h1>
    <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;">See how you rank against other learners on the platform.</p>
</div>

<!-- Your Rank Card -->
<div class="bg-surface border-4 border-gray-600 rounded-none p-6 mb-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="text-center">
            <h3 class="font-bold text-lg text-white mb-1 pixel-font" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= htmlspecialchars($user['username']) ?></h3>
            <p class="text-sm text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #333333;">Your current ranking</p>
        </div>
        <div class="flex items-center gap-8">
            <div class="text-center">
                <p class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">#<?= $userRank ?: '-' ?></p>
                <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #333333;">Rank</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $user['level'] ?></p>
                <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #333333;">Level</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $user['xp'] ?></p>
                <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #333333;">XP</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $user['streak'] ?? 0 ?></p>
                <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #333333;">Streak</p>
            </div>
        </div>
    </div>
</div>

<!-- Leaderboard Table -->
<div class="bg-surface border-4 border-black rounded-none overflow-hidden" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <div class="px-6 py-4 border-b-4 border-gray-600 bg-surface" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #333333;">
        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300" style="text-shadow: 1px 1px 0px #333333;">Global Rankings</h3>
    </div>

    <?php if (empty($topPlayers)): ?>
    <div class="text-center py-16">
        <span class="text-4xl mb-4 block" style="filter: drop-shadow(2px 2px 0px #333333);">🏆</span>
        <p class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #333333;">No rankings yet. Complete challenges to get on the leaderboard!</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-4 border-gray-600 text-xs text-gray-300 uppercase tracking-wider font-bold bg-surface" style="text-shadow: 1px 1px 0px #333333;">
                    <th class="text-left px-6 py-3 w-16">Rank</th>
                    <th class="text-left px-6 py-3">Player</th>
                    <th class="text-center px-6 py-3">Level</th>
                    <th class="text-center px-6 py-3">XP</th>
                    <th class="text-center px-6 py-3">Challenges</th>
                    <th class="text-center px-6 py-3">Streak</th>
                    <th class="text-right px-6 py-3">Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topPlayers as $player):
                    $isCurrentUser = ($player['user_id'] ?? '') === $user['id'];
                    $rankIcon = match($player['rank']) {
                        1 => '🥇',
                        2 => '🥈',
                        3 => '🥉',
                        default => '#' . $player['rank'],
                    };
                ?>
                <tr class="border-b-4 border-gray-600 last:border-0 hover:bg-gray-600 transition <?= $isCurrentUser ? 'bg-gray-700' : '' ?>">
                    <td class="px-6 py-4 text-lg font-bold text-center" style="filter: drop-shadow(2px 2px 0px #333333);"><?= $rankIcon ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="font-bold pixel-font <?= $isCurrentUser ? 'text-white' : 'text-gray-300' ?>" style="text-shadow: 1px 1px 0px #333333;">
                                <?= htmlspecialchars($player['username']) ?>
                                <?= $isCurrentUser ? ' (You)' : '' ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;"><?= $player['level'] ?></td>
                    <td class="px-6 py-4 text-center text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;"><?= $player['xp'] ?></td>
                    <td class="px-6 py-4 text-center text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;"><?= $player['challenges_completed'] ?></td>
                    <td class="px-6 py-4 text-center text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;">
                        <?= $player['streak'] > 0 ? '🔥 ' . $player['streak'] : '-' ?>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-sm" style="text-shadow: 1px 1px 0px #000;"><?= number_format($player['score'] ?? 0) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
