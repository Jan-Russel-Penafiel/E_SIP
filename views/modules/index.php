<!-- Modules Listing View -->
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-1 text-white" style="text-shadow: 2px 2px 0px #3f3f3f;">Learning Modules</h1>
    <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">Explore structured courses across multiple programming languages and frameworks.</p>
</div>

<!-- Filters -->
<div class="flex flex-wrap gap-3 mb-6">
    <!-- Language Filter -->
    <div class="relative">
        <select onchange="window.location.href=this.value"
                class="appearance-none bg-black border-4 border-black rounded-none px-4 py-2 pr-10 text-sm text-white font-bold focus:outline-none cursor-pointer" style="box-shadow: inset 4px 4px 0px #555, inset -4px -4px 0px #fff;">
            <option value="<?= $baseUrl ?>/modules" <?= !$filterLang ? 'selected' : '' ?>>All Languages</option>
            <?php foreach ($langConfig as $key => $lang): ?>
            <option class="lang-label" value="<?= $baseUrl ?>/modules?language=<?= $key ?><?= $filterDiff ? '&difficulty=' . $filterDiff : '' ?>"
                    <?= $filterLang === $key ? 'selected' : '' ?>>
                <?= $lang['icon'] ?> <?= $lang['name'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-white pointer-events-none" style="filter: drop-shadow(1px 1px 0px #000);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>

    <!-- Difficulty Filter -->
    <div class="relative">
        <select onchange="window.location.href=this.value"
                class="appearance-none bg-black border-4 border-black rounded-none px-4 py-2 pr-10 text-sm text-white font-bold focus:outline-none cursor-pointer" style="box-shadow: inset 4px 4px 0px #555, inset -4px -4px 0px #fff;">
            <option value="<?= $baseUrl ?>/modules<?= $filterLang ? '?language=' . $filterLang : '' ?>" <?= !$filterDiff ? 'selected' : '' ?>>All Levels</option>
            <?php foreach (['beginner', 'intermediate', 'advanced'] as $diff): ?>
            <option class="lang-label" value="<?= $baseUrl ?>/modules?<?= $filterLang ? 'language=' . $filterLang . '&' : '' ?>difficulty=<?= $diff ?>"
                    <?= $filterDiff === $diff ? 'selected' : '' ?>>
                <?= ucfirst($diff) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-white pointer-events-none" style="filter: drop-shadow(1px 1px 0px #000);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>

    <?php if ($filterLang || $filterDiff): ?>
    <a href="<?= $baseUrl ?>/modules"
       class="flex items-center gap-1 px-4 py-2 text-sm text-black font-bold bg-white border-4 border-black hover:bg-gray-200 transition" style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        Clear Filters
    </a>
    <?php endif; ?>
</div>

<!-- Modules Grid -->
<?php if (empty($modules)): ?>
<div class="text-center py-16">
    <span class="text-4xl mb-4 block" style="filter: drop-shadow(2px 2px 0px #000);">📚</span>
    <p class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">No modules found matching your filters.</p>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($modules as $module): ?>
    <a href="<?= $baseUrl ?>/modules/<?= $module['id'] ?>"
       class="group bg-surface border-4 border-black rounded-none p-6 card-hover transition-all duration-200" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <span class="text-3xl" style="filter: drop-shadow(2px 2px 0px #000);"><?= $module['icon'] ?></span>
            <div class="flex gap-2">
                <span class="px-2 py-1 text-xs font-bold bg-black text-gray-300 border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                    <?= ucfirst($module['difficulty']) ?>
                </span>
            </div>
        </div>

        <!-- Title & Description -->
        <h3 class="text-lg font-bold mb-2 text-white group-hover:text-gray-200 transition" style="text-shadow: 2px 2px 0px #3f3f3f;"><?= htmlspecialchars($module['title']) ?></h3>
        <p class="text-sm text-gray-300 mb-4 line-clamp-2 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($module['description']) ?></p>

        <!-- Meta -->
        <div class="flex items-center gap-4 text-xs text-gray-300 mb-4 font-bold" style="text-shadow: 1px 1px 0px #000;">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <?= count($module['lessons'] ?? []) ?> lessons
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <?= $module['total_xp'] ?> XP
            </span>
        </div>

        <!-- Progress Bar -->
        <div>
            <div class="flex justify-between text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span>Progress</span>
                <span><?= round($moduleProgress[$module['id']] ?? 0) ?>%</span>
            </div>
            <div class="h-3 bg-black border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                <div class="h-full bg-primary transition-all duration-500" style="width: <?= $moduleProgress[$module['id']] ?? 0 ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
