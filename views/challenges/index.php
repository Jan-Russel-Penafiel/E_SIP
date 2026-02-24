<!-- Challenges Listing View -->
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-1 text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">Coding Challenges</h1>
    <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">Test your skills with interactive coding challenges and quizzes.</p>
</div>

<!-- Filters -->
<div class="flex flex-wrap gap-3 mb-6">
    <!-- Language Filter -->
    <div class="relative">
        <select onchange="window.location.href=this.value"
                class="appearance-none bg-surface border-4 border-black rounded-none px-4 py-2 pr-10 text-sm text-white font-bold focus:outline-none cursor-pointer" style="box-shadow: inset 4px 4px 0px #555, inset -4px -4px 0px #fff;">
            <option value="<?= $baseUrl ?>/challenges" <?= !$filterLang ? 'selected' : '' ?>>All Languages</option>
            <?php foreach ($langConfig as $key => $lang): ?>
            <option class="lang-label" value="<?= $baseUrl ?>/challenges?language=<?= $key ?>"
                    <?= $filterLang === $key ? 'selected' : '' ?>>
                <?= $lang['icon'] ?> <?= $lang['name'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-white pointer-events-none" style="filter: drop-shadow(1px 1px 0px #000);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>

    <!-- Difficulty Filter -->
    <div class="relative">
        <select onchange="window.location.href=this.value"
                class="appearance-none bg-surface border-4 border-black rounded-none px-4 py-2 pr-10 text-sm text-white font-bold focus:outline-none cursor-pointer" style="box-shadow: inset 4px 4px 0px #555, inset -4px -4px 0px #fff;">
            <option value="<?= $baseUrl ?>/challenges<?= $filterLang ? '?language=' . $filterLang : '' ?>" <?= !$filterDiff ? 'selected' : '' ?>>All Levels</option>
            <?php foreach (['beginner', 'intermediate', 'advanced'] as $diff): ?>
            <option class="lang-label" value="<?= $baseUrl ?>/challenges?<?= $filterLang ? 'language=' . $filterLang . '&' : '' ?>difficulty=<?= $diff ?>"
                    <?= $filterDiff === $diff ? 'selected' : '' ?>>
                <?= ucfirst($diff) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-white pointer-events-none" style="filter: drop-shadow(1px 1px 0px #000);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>

    <?php if ($filterLang || $filterDiff): ?>
    <a href="<?= $baseUrl ?>/challenges"
       class="flex items-center gap-1 px-4 py-2 text-sm text-black font-bold bg-white border-4 border-black hover:bg-gray-200 transition" style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        Clear
    </a>
    <?php endif; ?>
</div>

<!-- Challenges Grid -->
<?php if (empty($challenges)): ?>
<div class="text-center py-16">
    <span class="text-4xl mb-4 block" style="filter: drop-shadow(2px 2px 0px #000);">⚡</span>
    <p class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">No challenges found.</p>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($challenges as $challenge):
        $isCompleted = in_array($challenge['id'], $user['completed_challenges'] ?? []);
        $langInfo = $langConfig[$challenge['language']] ?? ['name' => ucfirst($challenge['language']), 'icon' => '📝'];
    ?>
    <a href="<?= $baseUrl ?>/challenges/<?= $challenge['id'] ?>"
       class="group bg-surface border-4 border-black rounded-none p-5 card-hover transition-all duration-200 <?= $isCompleted ? 'opacity-75' : '' ?>" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <!-- Status Badge -->
        <div class="flex items-center justify-between mb-3">
            <span class="flex items-center gap-1.5 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span style="filter: drop-shadow(1px 1px 0px #000);"><?= $langInfo['icon'] ?></span>
                <span class="text-gray-300 lang-label"><?= $langInfo['name'] ?></span>
            </span>
            <?php if ($isCompleted): ?>
            <span class="text-xs text-green-400 font-bold flex items-center gap-1" style="text-shadow: 1px 1px 0px #000;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Done
            </span>
            <?php endif; ?>
        </div>

        <h3 class="font-bold mb-1 text-white group-hover:text-gray-200 transition" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= htmlspecialchars($challenge['title']) ?></h3>
        <p class="text-sm text-gray-300 mb-3 line-clamp-2 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($challenge['description']) ?></p>

        <div class="flex items-center gap-2 text-xs font-bold" style="text-shadow: 1px 1px 0px #000;">
            <span class="px-2 py-1 bg-surface text-gray-300 border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;"><?= ucfirst($challenge['difficulty']) ?></span>
            <span class="px-2 py-1 bg-surface text-gray-300 border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;"><?= ucfirst($challenge['type']) ?></span>
            <span class="ml-auto text-gray-300">+<?= $challenge['xp_reward'] ?> XP</span>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
