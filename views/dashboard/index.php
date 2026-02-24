<!-- Dashboard View -->
<!-- Hero Stats Cards -->
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-1 text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">Welcome back, <?= htmlspecialchars($user['username']) ?>!</h1>
    <p class="text-gray-300 text-sm" style="text-shadow: 1px 1px 0px #000;">Continue your coding journey. Here's your progress overview.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Level Card -->
    <div class="bg-surface border border-border rounded-none p-5 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-300 text-xs font-bold uppercase tracking-wider" style="text-shadow: 1px 1px 0px #000;">Level</span>
            <span class="text-2xl" style="filter: drop-shadow(2px 2px 0px #000);">🎯</span>
        </div>
        <p class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $user['level'] ?></p>
        <div class="mt-3">
            <div class="flex justify-between text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span><?= $user['xp'] ?> XP</span>
                <span><?= $xpNeeded ?> XP</span>
            </div>
            <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                <div class="h-full bg-primary transition-all duration-500" style="width: <?= $xpPercent ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
            </div>
        </div>
    </div>

    <!-- Challenges Completed -->
    <div class="bg-surface border border-border rounded-none p-5 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-300 text-xs font-bold uppercase tracking-wider" style="text-shadow: 1px 1px 0px #000;">Challenges</span>
            <span class="text-2xl" style="filter: drop-shadow(2px 2px 0px #000);">⚡</span>
        </div>
        <p class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= count($user['completed_challenges'] ?? []) ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">completed</p>
    </div>

    <!-- Streak -->
    <div class="bg-surface border border-border rounded-none p-5 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-300 text-xs font-bold uppercase tracking-wider" style="text-shadow: 1px 1px 0px #000;">Streak</span>
            <span class="text-2xl" style="filter: drop-shadow(2px 2px 0px #000);">🔥</span>
        </div>
        <p class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= $user['streak'] ?? 0 ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">day<?= ($user['streak'] ?? 0) !== 1 ? 's' : '' ?></p>
    </div>

    <!-- Rank -->
    <div class="bg-surface border border-border rounded-none p-5 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-300 text-xs font-bold uppercase tracking-wider" style="text-shadow: 1px 1px 0px #000;">Rank</span>
            <span class="text-2xl" style="filter: drop-shadow(2px 2px 0px #000);">🏆</span>
        </div>
        <p class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">#<?= $userRank ?: '-' ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">global</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <!-- Weekly Activity Chart -->
    <div class="lg:col-span-2 bg-surface border border-border rounded-none p-6 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Weekly Activity</h3>
        <div class="flex items-end justify-between gap-2 h-40">
            <?php
            $maxAttempts = max(array_column($activity, 'attempts') ?: [1]);
            foreach ($activity as $day):
                $height = $maxAttempts > 0 ? ($day['attempts'] / $maxAttempts) * 100 : 0;
                $height = max($height, 4); // Minimum visible height
            ?>
            <div class="flex-1 flex flex-col items-center gap-2">
                <span class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= $day['xp'] ?></span>
                <div class="w-full bg-black border-2 border-gray-600 relative" style="height: 120px; box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                    <div class="absolute bottom-0 w-full bg-primary transition-all duration-700"
                         style="height: <?= $height ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                </div>
                <span class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= $day['label'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-surface border border-border rounded-none p-6 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Performance</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <span class="text-gray-300">Success Rate</span>
                    <span class="text-white"><?= $analytics['success_rate'] ?>%</span>
                </div>
                <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                    <div class="h-full bg-primary" style="width: <?= $analytics['success_rate'] ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                </div>
            </div>
            <div class="flex justify-between text-sm py-2 border-t-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span class="text-gray-300">Total Attempts</span>
                <span class="text-white"><?= $analytics['total_attempts'] ?></span>
            </div>
            <div class="flex justify-between text-sm py-2 border-t-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span class="text-gray-300">Successful</span>
                <span class="text-white"><?= $analytics['successful'] ?></span>
            </div>
            <div class="flex justify-between text-sm py-2 border-t-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span class="text-gray-300">Total XP Earned</span>
                <span class="text-white"><?= $analytics['total_xp'] ?></span>
            </div>
            <div class="flex justify-between text-sm py-2 border-t-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span class="text-gray-300">Avg. Time</span>
                <span class="text-white"><?= $analytics['average_time'] ?>s</span>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <!-- Daily Challenge -->
    <?php if ($dailyChallenge): ?>
    <div class="bg-surface border border-border rounded-none p-6 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300" style="text-shadow: 1px 1px 0px #000;">Daily Challenge</h3>
            <span class="px-2 py-1 text-xs font-bold bg-white text-black border-2 border-black" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;">
                +<?= $dailyChallenge['xp_reward'] ?> XP
            </span>
        </div>
        <h4 class="text-lg font-bold mb-2 text-white" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= htmlspecialchars($dailyChallenge['title']) ?></h4>
        <p class="text-gray-300 text-sm mb-4 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($dailyChallenge['description']) ?></p>
        <div class="flex items-center gap-3">
            <span class="px-2 py-1 text-xs font-bold bg-black text-gray-300 border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                <?= ucfirst($dailyChallenge['difficulty']) ?>
            </span>
            <span class="px-2 py-1 text-xs font-bold bg-black text-gray-300 border-2 border-gray-600 lang-label" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                <?= ucfirst($dailyChallenge['language']) ?>
            </span>
        </div>
        <a href="<?= $baseUrl ?>/challenges/<?= $dailyChallenge['id'] ?>"
           class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-white text-black text-sm font-bold border-4 border-black hover:bg-gray-200 transition" style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc;">
            Start Challenge
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
    <?php endif; ?>

    <!-- Language Distribution -->
    <div class="bg-surface border border-border rounded-none p-6 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Languages Practiced</h3>
        <?php if (empty($langDist)): ?>
            <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">Complete challenges to see your language distribution.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php
                $totalLang = array_sum($langDist);
                $config = require __DIR__ . '/../../config/app.php';
                foreach ($langDist as $lang => $count):
                    $percent = $totalLang > 0 ? round(($count / $totalLang) * 100) : 0;
                    $langName = $config['languages'][$lang]['name'] ?? ucfirst($lang);
                    $langIcon = $config['languages'][$lang]['icon'] ?? '📝';
                ?>
                <div>
                    <div class="flex justify-between text-sm mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                        <span class="flex items-center gap-2 text-white">
                            <span style="filter: drop-shadow(1px 1px 0px #000);"><?= $langIcon ?></span>
                            <span class="lang-label"><?= $langName ?></span>
                        </span>
                        <span class="text-gray-300"><?= $count ?> (<?= $percent ?>%)</span>
                    </div>
                    <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                        <div class="h-full bg-primary" style="width: <?= $percent ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Module Progress -->
<div class="bg-surface border border-border rounded-none p-6 card-hover" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555; border: 4px solid #000;">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300" style="text-shadow: 1px 1px 0px #000;">Module Progress</h3>
        <a href="<?= $baseUrl ?>/modules" class="text-sm text-gray-300 hover:text-white transition font-bold" style="text-shadow: 1px 1px 0px #000;">View All →</a>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach (array_slice($modules, 0, 4) as $module): ?>
        <a href="<?= $baseUrl ?>/modules/<?= $module['id'] ?>"
           class="block p-4 bg-black border-4 border-black hover:border-gray-600 transition group" style="box-shadow: inset 4px 4px 0px #000, inset -4px -4px 0px #fff;">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xl" style="filter: drop-shadow(2px 2px 0px #000);"><?= $module['icon'] ?></span>
                <span class="text-sm font-bold truncate text-gray-300 group-hover:text-white" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($module['title']) ?></span>
            </div>
            <div class="mb-1 flex justify-between text-xs text-gray-400 font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span><?= ucfirst($module['difficulty']) ?></span>
                <span><?= round($moduleProgress[$module['id']] ?? 0) ?>%</span>
            </div>
            <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                <div class="h-full bg-primary transition-all" style="width: <?= $moduleProgress[$module['id']] ?? 0 ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
