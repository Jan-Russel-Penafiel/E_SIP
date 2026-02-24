<!-- Profile View -->
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-1 text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">Profile</h1>
    <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">View your stats, badges, and manage your learning preferences.</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left: Profile Card -->
    <div>
        <!-- User Card -->
        <div class="bg-surface border-4 border-black rounded-none p-6 mb-4" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto bg-white text-black flex items-center justify-center text-2xl font-bold mb-3 border-4 border-black" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;">
                    <?= strtoupper(substr($user['username'], 0, 2)) ?>
                </div>
                <h2 class="text-xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= htmlspecialchars($user['username']) ?></h2>
                <p class="text-sm text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($user['email']) ?></p>
                <p class="text-xs text-gray-400 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
            </div>

            <!-- Level Progress -->
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <span class="text-gray-300">Level <?= $user['level'] ?></span>
                    <span class="text-gray-300"><?= $user['xp'] ?> / <?= $xpNeeded ?> XP</span>
                </div>
                <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                    <div class="h-full bg-primary transition-all duration-500" style="width: <?= $xpPercent ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="bg-black border-4 border-black rounded-none p-3" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-lg font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= count($user['completed_challenges'] ?? []) ?></p>
                    <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">Solved</p>
                </div>
                <div class="bg-black border-4 border-black rounded-none p-3" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-lg font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $user['streak'] ?? 0 ?></p>
                    <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">Streak</p>
                </div>
                <div class="bg-black border-4 border-black rounded-none p-3" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-lg font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= count($user['badges'] ?? []) ?></p>
                    <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">Badges</p>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Settings</h3>
            <form method="POST" action="<?= $baseUrl ?>/profile/settings">
                <label class="block text-sm text-gray-300 mb-2 font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty Preference</label>
                <div class="space-y-2 mb-4">
                    <?php foreach (['beginner', 'intermediate', 'advanced'] as $diff): ?>
                    <label class="flex items-center gap-3 px-3 py-2 bg-black border-4 border-black rounded-none cursor-pointer hover:bg-gray-800 transition" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                           <input type="radio" name="difficulty" value="<?= $diff ?>"
                               <?= ($user['difficulty'] ?? 'beginner') === $diff ? 'checked' : '' ?>
                               class="w-4 h-4" style="accent-color: #ffffff;">
                        <span class="text-sm font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= ucfirst($diff) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-white text-black text-sm font-bold border-4 border-black hover:bg-gray-200 transition" style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc;">
                    Save Settings
                </button>
            </form>
        </div>
    </div>

    <!-- Middle + Right: Stats & Badges -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Performance Analytics -->
        <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Performance Analytics</h3>
            <div class="grid md:grid-cols-4 gap-4 mb-6">
                <div class="bg-black border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $analytics['total_attempts'] ?></p>
                    <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Total Attempts</p>
                </div>
                <div class="bg-black border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $analytics['success_rate'] ?>%</p>
                    <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Success Rate</p>
                </div>
                <div class="bg-black border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $analytics['total_xp'] ?></p>
                    <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Total XP</p>
                </div>
                <div class="bg-black border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $analytics['average_time'] ?>s</p>
                    <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Avg. Time</p>
                </div>
            </div>

            <!-- Success rate bar -->
            <div class="mb-2">
                <div class="flex justify-between text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <span>Overall Success Rate</span>
                    <span><?= $analytics['success_rate'] ?>%</span>
                </div>
                <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                    <div class="h-full bg-primary transition-all" style="width: <?= $analytics['success_rate'] ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                </div>
            </div>
        </div>

        <!-- Badges -->
        <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Badges & Achievements</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <?php foreach ($badgeInfo as $badgeKey => $badge):
                    $earned = in_array($badgeKey, $user['badges'] ?? []);
                ?>
                <div class="p-4 bg-black border-4 border-black rounded-none text-center <?= $earned ? '' : 'opacity-40' ?> transition" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                    <span class="text-3xl block mb-2" style="filter: drop-shadow(2px 2px 0px #000);"><?= $badge['icon'] ?></span>
                    <p class="text-sm font-bold mb-1 text-white" style="text-shadow: 1px 1px 0px #000;"><?= $badge['label'] ?></p>
                    <p class="text-xs text-gray-400 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= $badge['desc'] ?></p>
                    <?php if ($earned): ?>
                    <span class="inline-block mt-2 text-xs text-green-400 font-bold" style="text-shadow: 1px 1px 0px #000;">✓ Earned</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Language Distribution -->
        <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Language Activity</h3>
            <?php if (empty($langDist)): ?>
            <p class="text-gray-300 text-sm text-center py-4 font-bold" style="text-shadow: 1px 1px 0px #000;">Complete some challenges to see your language distribution here.</p>
            <?php else: ?>
            <div class="space-y-3">
                <?php
                $totalLang = array_sum($langDist);
                foreach ($langDist as $lang => $count):
                    $percent = $totalLang > 0 ? round(($count / $totalLang) * 100) : 0;
                    $langName = $langConfig[$lang]['name'] ?? ucfirst($lang);
                    $langIcon = $langConfig[$lang]['icon'] ?? '📝';
                ?>
                <div>
                    <div class="flex justify-between text-sm mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                        <span class="flex items-center gap-2 text-white"><span style="filter: drop-shadow(1px 1px 0px #000);"><?= $langIcon ?></span> <?= $langName ?></span>
                        <span class="text-gray-300"><?= $count ?> attempts (<?= $percent ?>%)</span>
                    </div>
                    <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                        <div class="h-full bg-primary" style="width: <?= $percent ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Recent Activity -->
        <?php if (!empty($analytics['recent'])): ?>
        <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Recent Activity</h3>
            <div class="space-y-2">
                <?php foreach ($analytics['recent'] as $attempt): ?>
                <div class="flex items-center justify-between py-2 border-b-4 border-black last:border-0 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <div class="flex items-center gap-3">
                        <span class="<?= $attempt['success'] ? 'text-green-400' : 'text-red-400' ?>">
                            <?= $attempt['success'] ? '✓' : '✗' ?>
                        </span>
                        <span class="text-white"><?= $attempt['challenge_id'] ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-300">
                        <span><?= $attempt['xp_earned'] ?> XP</span>
                        <span><?= $attempt['time_taken'] ?>s</span>
                        <span><?= date('M j, H:i', strtotime($attempt['attempted_at'])) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
