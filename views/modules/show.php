<!-- Module Detail View -->
<?php
$langInfo = $langConfig[$module['language']] ?? ['name' => ucfirst($module['language']), 'icon' => '📝'];
$isModuleCompleted = $progress >= 100;

// Map module language → Prism.js grammar class
$prismLangMap = [
    'python'     => 'python',
    'javascript' => 'javascript',
    'js'         => 'javascript',
    'nodejs'     => 'javascript',
    'react'      => 'jsx',
    'vue'        => 'javascript',
    'java'       => 'java',
    'php'        => 'php',
    'laravel'    => 'php',
    'typescript' => 'typescript',
    'django'     => 'python',
    'sql'        => 'sql',
    'css'        => 'css',
    'html'       => 'html',
    'c'          => 'c',
    'cpp'        => 'cpp',
];
$prismLang = $prismLangMap[$module['language']] ?? 'clike';
?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= $baseUrl ?>/modules" class="hover:text-white transition">Modules</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    <span class="text-white"><?= htmlspecialchars($module['title']) ?></span>
</nav>

<!-- Module Header -->
<div class="bg-surface border-4 border-gray-600 rounded-none p-8 mb-8" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <div class="flex flex-col md:flex-row md:items-start gap-6">
            <div class="flex-shrink-0">
            <div class="w-16 h-16 bg-surface border-4 border-gray-600 rounded-none flex items-center justify-center text-3xl" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #333333;">
                <span style="filter: drop-shadow(2px 2px 0px #333333);"><?= $module['icon'] ?></span>
            </div>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #333333;"><?= htmlspecialchars($module['title']) ?></h1>
                <?php if ($isModuleCompleted): ?>
                <span class="px-2 py-1 text-xs font-bold rounded-none bg-green-500 text-black border-2 border-gray-600" style="box-shadow: 2px 2px 0px #333333;">✓ Completed</span>
                <?php endif; ?>
            </div>
            <p class="text-gray-300 mb-4 font-bold" style="text-shadow: 1px 1px 0px #333333;"><?= htmlspecialchars($module['description']) ?></p>

            <div class="flex flex-wrap items-center gap-4 text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;">
                <span class="flex items-center gap-1.5 text-white lang-label">
                    <span style="filter: drop-shadow(1px 1px 0px #333333);"><?= $langInfo['icon'] ?></span> <?= $langInfo['name'] ?>
                </span>
                <span class="px-2 py-0.5 text-xs rounded-none bg-surface text-white border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #333333, inset -2px -2px 0px #555;">
                    <?= ucfirst($module['difficulty']) ?>
                </span>
                <span class="text-gray-300"><?= count($module['lessons'] ?? []) ?> lessons</span>
                <span class="text-gray-300"><?= $module['total_xp'] ?> XP total</span>
            </div>

            <!-- Progress -->
            <div class="mt-4 max-w-md">
                <div class="flex justify-between text-xs text-white font-bold mb-1" style="text-shadow: 1px 1px 0px #000;">
                    <span>Progress</span>
                    <span id="progressText"><?= round($progress) ?>%</span>
                </div>
                <div class="h-3 bg-surface border-2 border-gray-600 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #fff;">
                    <div id="progressBar" class="h-full bg-primary transition-all duration-500" style="width: <?= $progress ?>%; box-shadow: inset 2px 2px 0px #86efac, inset -2px -2px 0px #166534;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Lessons Column -->
    <div class="lg:col-span-2">
        <h2 class="text-lg font-bold mb-4 text-white" style="text-shadow: 2px 2px 0px #000;">Lessons</h2>
        <div class="space-y-3">
            <?php foreach ($module['lessons'] ?? [] as $index => $lesson):
                $game = $lesson['game'] ?? null;
                $gameId = 'game_' . $index;
                $lessonDetails = $lesson['details'] ?? $lesson['content'];
            ?>
            <div class="bg-surface border-4 border-black rounded-none overflow-hidden transition lesson-card" id="lesson-<?= $index ?>" data-lesson-id="<?= htmlspecialchars($lesson['id']) ?>" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
                <!-- Lesson Header (always visible) -->
                <div class="flex items-center gap-4 p-5 cursor-pointer hover:bg-gray-800 transition-colors" onclick="toggleLesson(<?= $index ?>)">
                    <div class="flex-shrink-0 w-8 h-8 rounded-none bg-surface border-2 border-gray-600 flex items-center justify-center text-sm font-bold text-white" id="lessonNum-<?= $index ?>" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #555;">
                        <?= $index + 1 ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold truncate text-white" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($lesson['title']) ?></h3>
                            <span id="lessonBadge-<?= $index ?>" class="hidden px-2 py-0.5 text-xs font-bold rounded-none bg-green-500 text-black border-2 border-black" style="box-shadow: 2px 2px 0px #000;">✓ Done</span>
                        </div>
                        <p class="text-sm text-gray-300 truncate font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($lesson['content']) ?></p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">⚡ <?= $lesson['xp'] ?> XP</span>
                        <svg class="w-5 h-5 text-white transition-transform lesson-chevron" id="chevron-<?= $index ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(1px 1px 0px #000);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Lesson Body (expandable) -->
                <div id="lessonBody-<?= $index ?>" class="hidden border-t-4 border-black">
                    <!-- Phase 1: Lesson Information -->
                    <div id="lessonInfo-<?= $index ?>" class="p-5">
                        <div class="bg-surface border-4 border-black rounded-none p-6 mb-4" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg" style="filter: drop-shadow(2px 2px 0px #000);">📖</span>
                                <h4 class="font-bold text-white" style="text-shadow: 1px 1px 0px #000;">Lesson Content</h4>
                            </div>
                            <div class="text-sm text-gray-300 leading-relaxed space-y-3 lesson-content-text font-bold" style="text-shadow: 1px 1px 0px #000;">
                                <?php if (is_array($lessonDetails)): ?>
                                    <?php foreach ($lessonDetails as $detail): ?>
                                    <p><?= htmlspecialchars($detail) ?></p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p><?= htmlspecialchars($lessonDetails) ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($lesson['example_code'])): ?>
                            <div class="mt-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2" style="text-shadow: 1px 1px 0px #000;">Example</p>
                                <pre class="bg-surface p-3 border-4 border-black rounded-none text-sm overflow-x-auto" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333; background-color: #333333;"><code class="language-<?= $prismLang ?>"><?= htmlspecialchars($lesson['example_code']) ?></code></pre>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($lesson['key_points'])): ?>
                            <div class="mt-4 p-3 bg-gray-900 border-4 border-black rounded-none" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;">
                                <p class="text-xs font-bold text-yellow-400 mb-2" style="text-shadow: 1px 1px 0px #000;">💡 Key Points</p>
                                <ul class="text-sm text-gray-300 space-y-1 list-disc list-inside font-bold" style="text-shadow: 1px 1px 0px #000;">
                                    <?php foreach ($lesson['key_points'] as $point): ?>
                                    <li><?= htmlspecialchars($point) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($game): ?>
                        <button onclick="showGame(<?= $index ?>)" class="w-full py-3 text-sm font-bold rounded-none bg-white text-black border-4 border-black hover:bg-gray-200 transition flex items-center justify-center gap-2" id="startGameBtn-<?= $index ?>" style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc, 4px 4px 0px #000;">
                            🎮 Start Game Challenge
                        </button>
                        <?php endif; ?>
                    </div>

                    <!-- Phase 2: Game -->
                    <?php if ($game): ?>
                    <div id="lessonGame-<?= $index ?>" class="hidden p-5">
                        <!-- Back to Lesson button (visible during game, hidden when completed) -->
                        <button onclick="backToInfo(<?= $index ?>)" class="mb-4 text-sm text-gray-300 hover:text-white transition flex items-center gap-1 font-bold" id="backBtn-<?= $index ?>" style="text-shadow: 1px 1px 0px #000;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(1px 1px 0px #000);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Back to Lesson
                        </button>

                        <div class="bg-surface border-4 border-black rounded-none p-5 game-panel" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg" style="filter: drop-shadow(2px 2px 0px #000);">🎮</span>
                                <p class="text-sm font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($game['question']) ?></p>
                            </div>

                            <?php if ($game['type'] === 'fill_blank'): ?>
                            <pre class="bg-gray-900 p-3 border-4 border-black rounded-none text-sm mb-3 overflow-x-auto" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;"><code class="language-<?= $prismLang ?>"><?= htmlspecialchars($game['code_template']) ?></code></pre>
                            <div class="grid grid-cols-2 gap-2" id="options-<?= $gameId ?>">
                                <?php foreach ($game['options'] as $opt): ?>
                                <button onclick="checkAnswer(this, '<?= $gameId ?>', '<?= addslashes($opt) ?>', '<?= addslashes($game['answer']) ?>', <?= $index ?>)"
                                    class="game-option px-3 py-2 text-sm rounded-none bg-gray-800 border-4 border-black text-white hover:bg-gray-700 transition text-left font-bold" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444;">
                                    <?= htmlspecialchars($opt) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>

                            <?php elseif ($game['type'] === 'predict_output'): ?>
                            <pre class="bg-gray-900 p-3 border-4 border-black rounded-none text-sm mb-3 overflow-x-auto" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;"><code class="language-<?= $prismLang ?>"><?= htmlspecialchars($game['code']) ?></code></pre>
                            <div class="grid grid-cols-2 gap-2" id="options-<?= $gameId ?>">
                                <?php foreach ($game['options'] as $opt): ?>
                                <button onclick="checkAnswer(this, '<?= $gameId ?>', '<?= addslashes($opt) ?>', '<?= addslashes($game['answer']) ?>', <?= $index ?>)"
                                    class="game-option px-3 py-2 text-sm rounded-none bg-gray-800 border-4 border-black text-white hover:bg-gray-700 transition text-left font-bold" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444;">
                                    <?= htmlspecialchars($opt) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>

                            <?php elseif ($game['type'] === 'match_pairs'): ?>
                            <div class="grid grid-cols-2 gap-4" id="match-<?= $gameId ?>">
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Values</p>
                                    <?php foreach ($game['pairs'] as $pi => $pair): ?>
                                    <button onclick="selectMatchItem(this, '<?= $gameId ?>', 'left', <?= $pi ?>, <?= $index ?>)"
                                        class="match-left w-full px-3 py-2 text-sm rounded-none bg-gray-800 border-4 border-black text-white hover:bg-gray-700 transition text-left font-bold" data-idx="<?= $pi ?>" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444;">
                                        <?= htmlspecialchars($pair[0]) ?>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Types</p>
                                    <?php
                                    $shuffledPairs = $game['pairs'];
                                    $shuffledPairs = array_reverse($shuffledPairs);
                                    foreach ($shuffledPairs as $pi => $pair): ?>
                                    <button onclick="selectMatchItem(this, '<?= $gameId ?>', 'right', '<?= addslashes($pair[1]) ?>', <?= $index ?>)"
                                        class="match-right w-full px-3 py-2 text-sm rounded-none bg-gray-800 border-4 border-black text-white hover:bg-gray-700 transition text-left font-bold" data-val="<?= htmlspecialchars($pair[1]) ?>" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444;">
                                        <?= htmlspecialchars($pair[1]) ?>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php elseif ($game['type'] === 'reorder_code'): ?>
                            <div id="reorder-<?= $gameId ?>" class="space-y-2 mb-3">
                                <?php
                                $lines = $game['lines'];
                                $shuffled = array_reverse($lines);
                                foreach ($shuffled as $li => $line): ?>
                                <div draggable="true" class="reorder-line cursor-move px-3 py-2 text-sm rounded-none bg-gray-800 border-4 border-black text-green-400 font-bold flex items-center gap-2 hover:bg-gray-700 transition"
                                     data-original="<?= htmlspecialchars($line) ?>"
                                     ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="dropLine(event)" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444; text-shadow: 1px 1px 0px #000;">
                                    <span class="text-gray-500" style="filter: drop-shadow(1px 1px 0px #000);">⣿</span> <?= htmlspecialchars($line) ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button onclick="checkReorder('<?= $gameId ?>', <?= htmlspecialchars(json_encode($game['lines'])) ?>, <?= $index ?>)"
                                class="px-4 py-2 text-xs font-bold rounded-none bg-white text-black border-4 border-black hover:bg-gray-200 transition" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc, 2px 2px 0px #000;">
                                Check Order
                            </button>

                            <?php elseif ($game['type'] === 'fix_bug'): ?>
                            <div class="mb-2">
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Buggy code</p>
                                <pre class="bg-gray-900 p-3 border-4 border-black rounded-none text-sm overflow-x-auto opacity-60 line-through" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;"><code class="language-<?= $prismLang ?>"><?= htmlspecialchars($game['code']) ?></code></pre>
                            </div>
                            <?php if (!empty($game['hint'])): ?>
                            <p class="text-xs text-yellow-400 mb-2 font-bold" style="text-shadow: 1px 1px 0px #000;">💡 Hint: <?= htmlspecialchars($game['hint']) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Your fix</p>
                            <div class="prism-editor-wrap rounded-none border-4 border-black overflow-hidden" style="height:6rem; box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;" id="fixWrap-<?= $gameId ?>">
                                <pre id="fixPre-<?= $gameId ?>" aria-hidden="true" style="height:6rem;overflow:auto;padding:0.75rem;"><code id="fixCode-<?= $gameId ?>" class="language-<?= $prismLang ?>"></code></pre>
                                <textarea id="fix-<?= $gameId ?>"
                                          class="fix-bug-textarea bg-transparent text-white font-mono"
                                          style="height:6rem;"
                                          spellcheck="false"
                                          autocomplete="off"
                                          placeholder="Type the corrected code here…"><?= htmlspecialchars($game['code']) ?></textarea>
                            </div>
                            <div class="flex gap-2 mt-2">
                                <button onclick="runFix('<?= $gameId ?>')"
                                    class="px-3 py-2 text-xs font-bold rounded-none bg-gray-800 border-4 border-black text-white hover:bg-gray-700 transition flex items-center gap-1.5" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444; text-shadow: 1px 1px 0px #000;">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(1px 1px 0px #000);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                    Run
                                </button>
                                <button onclick="checkFix('<?= $gameId ?>', <?= htmlspecialchars(json_encode($game['answer'])) ?>, <?= $index ?>)"
                                    class="px-4 py-2 text-xs font-bold rounded-none bg-white text-black border-4 border-black hover:bg-gray-200 transition" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc, 2px 2px 0px #000;">
                                    Submit Fix
                                </button>
                            </div>
                            <div id="fixOutput-<?= $gameId ?>" class="hidden mt-2 bg-gray-900 border-4 border-black rounded-none p-3 font-bold text-xs text-gray-300 max-h-24 overflow-y-auto" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333; text-shadow: 1px 1px 0px #000;"></div>
                            <?php endif; ?>

                            <!-- Result feedback -->
                            <div id="result-<?= $gameId ?>" class="hidden mt-3 p-3 rounded-none border-4 border-black text-sm font-bold" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc; text-shadow: 1px 1px 0px #000;"></div>

                            <!-- Try Again / Back to Lesson (shown on fail) -->
                            <div id="failActions-<?= $index ?>" class="hidden mt-3 flex gap-2">
                                <button onclick="backToInfo(<?= $index ?>)" class="flex-1 py-2 text-sm font-bold rounded-none bg-gray-800 border-4 border-black text-white hover:bg-gray-700 transition flex items-center justify-center gap-1" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444; text-shadow: 1px 1px 0px #000;">
                                    📖 Review Lesson
                                </button>
                                <button onclick="retryGame(<?= $index ?>)" class="flex-1 py-2 text-sm font-bold rounded-none bg-yellow-500 border-4 border-black text-black hover:bg-yellow-400 transition flex items-center justify-center gap-1" style="box-shadow: inset 2px 2px 0px #fde047, inset -2px -2px 0px #a16207;">
                                    🔄 Try Again
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Phase 3: Completed -->
                    <div id="lessonComplete-<?= $index ?>" class="hidden p-5">
                        <div class="bg-green-500 border-4 border-black rounded-none p-6 text-center" style="box-shadow: inset 4px 4px 0px #86efac, inset -4px -4px 0px #166534;">
                            <span class="text-4xl block mb-2" style="filter: drop-shadow(2px 2px 0px #000);">🎉</span>
                            <h4 class="text-lg font-bold text-black mb-1">Lesson Completed!</h4>
                            <p class="text-sm text-black font-bold">You earned <span class="text-white" style="text-shadow: 1px 1px 0px #000;">⚡ <?= $lesson['xp'] ?> XP</span></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Challenges Sidebar -->
    <div>
        <h2 class="text-lg font-bold mb-4 text-white" style="text-shadow: 2px 2px 0px #333333;">Challenges</h2>
        <?php if (empty($challenges)): ?>
            <div class="bg-surface border-4 border-gray-600 rounded-none p-6 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
                <span class="text-3xl block mb-2" style="filter: drop-shadow(2px 2px 0px #333333);">🎮</span>
                <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #333333;">No challenges available for this module yet.</p>
            </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($challenges as $challenge):
                $isCompleted = in_array($challenge['id'], $user['completed_challenges'] ?? []);
            ?>
                <a href="<?= $baseUrl ?>/challenges/<?= $challenge['id'] ?>"
                   class="block bg-surface border-4 border-gray-600 rounded-none p-4 hover:bg-gray-800 transition group" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
                <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-sm text-white group-hover:text-yellow-400 transition" style="text-shadow: 1px 1px 0px #333333;"><?= htmlspecialchars($challenge['title']) ?></h4>
                    <?php if ($isCompleted): ?>
                        <span class="text-green-400 text-xs font-bold" style="text-shadow: 1px 1px 0px #333333;">✓</span>
                    <?php endif; ?>
                </div>
                    <div class="flex items-center gap-2 text-xs text-white font-bold" style="text-shadow: 1px 1px 0px #333333;">
                        <span class="px-1.5 py-0.5 rounded-none bg-surface text-gray-300 border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #333333, inset -2px -2px 0px #fff;"><?= ucfirst($challenge['difficulty']) ?></span>
                        <span class="px-1.5 py-0.5 rounded-none bg-surface text-gray-300 border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #333333, inset -2px -2px 0px #fff;"><?= ucfirst($challenge['type']) ?></span>
                        <span class="text-yellow-400">+<?= $challenge['xp_reward'] ?> XP</span>
                    </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Game Interaction Scripts -->
<script>
const moduleId = '<?= $module['id'] ?>';
const lessonData = <?= json_encode($module['lessons'] ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const totalLessons = lessonData.length;

// Completed lessons storage key
const storageKey = 'esip_completed_lessons_' + moduleId;

// Get completed lessons from localStorage
function getCompletedLessons() {
    try { return JSON.parse(localStorage.getItem(storageKey) || '[]'); } catch(e) { return []; }
}
function saveCompletedLesson(lessonId) {
    const completed = getCompletedLessons();
    if (!completed.includes(lessonId)) {
        completed.push(lessonId);
        localStorage.setItem(storageKey, JSON.stringify(completed));
    }
}
function isLessonCompleted(lessonId) {
    return getCompletedLessons().includes(lessonId);
}

// Initialize lesson states on load
function initLessonStates() {
    for (let i = 0; i < totalLessons; i++) {
        const lessonId = lessonData[i].id;
        if (isLessonCompleted(lessonId)) {
            markLessonCompleted(i, false);
        }
    }
    updateProgressBar();
}

// Toggle lesson expand/collapse
function toggleLesson(index) {
    const body = document.getElementById('lessonBody-' + index);
    const chevron = document.getElementById('chevron-' + index);
    const lessonId = lessonData[index].id;

    // If completed, only show the completed state
    if (isLessonCompleted(lessonId)) {
        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
            // Show only completed phase
            document.getElementById('lessonInfo-' + index).classList.add('hidden');
            const gameEl = document.getElementById('lessonGame-' + index);
            if (gameEl) gameEl.classList.add('hidden');
            document.getElementById('lessonComplete-' + index).classList.remove('hidden');
        } else {
            body.classList.add('hidden');
            chevron.style.transform = '';
        }
        return;
    }

    if (body.classList.contains('hidden')) {
        body.classList.remove('hidden');
        body.style.animation = 'fadeSlideIn 0.3s ease';
        chevron.style.transform = 'rotate(180deg)';
        // Show info phase by default
        document.getElementById('lessonInfo-' + index).classList.remove('hidden');
        const gameEl = document.getElementById('lessonGame-' + index);
        if (gameEl) gameEl.classList.add('hidden');
        const completeEl = document.getElementById('lessonComplete-' + index);
        if (completeEl) completeEl.classList.add('hidden');
    } else {
        body.classList.add('hidden');
        chevron.style.transform = '';
    }
}

// Show game phase
function showGame(index) {
    // Remove any lingering retry notification
    const existingNotif = document.getElementById('retryNotif-' + index);
    if (existingNotif) existingNotif.remove();
    document.getElementById('lessonInfo-' + index).classList.add('hidden');
    const gameEl = document.getElementById('lessonGame-' + index);
    if (gameEl) {
        gameEl.classList.remove('hidden');
        gameEl.style.animation = 'fadeSlideIn 0.3s ease';
    }
    // Reset game state so each attempt starts fresh
    retryGame(index);
}

// Back to info phase
function backToInfo(index) {
    const gameEl = document.getElementById('lessonGame-' + index);
    if (gameEl) gameEl.classList.add('hidden');
    document.getElementById('lessonInfo-' + index).classList.remove('hidden');
    document.getElementById('lessonInfo-' + index).style.animation = 'fadeSlideIn 0.3s ease';
}

// Retry game (reset game state)
function retryGame(index) {
    const gameId = 'game_' + index;
    // Hide fail actions
    document.getElementById('failActions-' + index).classList.add('hidden');
    // Hide result
    document.getElementById('result-' + gameId).classList.add('hidden');
    // Reset options
    const optionsEl = document.getElementById('options-' + gameId);
    if (optionsEl) {
        optionsEl.querySelectorAll('.game-option').forEach(o => {
            o.disabled = false;
            o.className = 'game-option px-3 py-2 text-sm rounded-lg bg-[#2a2a2a] border border-[#444] text-gray-300 hover:border-white hover:text-white transition text-left font-mono';
        });
    }
    // Reset match pairs
    if (matchState[gameId]) {
        matchState[gameId] = { left: null, right: null, matched: 0, pairs: [] };
    }
    const matchEl = document.getElementById('match-' + gameId);
    if (matchEl) {
        matchEl.querySelectorAll('button').forEach(b => {
            b.className = b.className.replace(/bg-green-900\/60|border-green-500|text-green-300|bg-red-900\/60|border-red-500|pointer-events-none/g, '').trim();
            b.classList.add('bg-[#2a2a2a]', 'border-[#444]', 'text-gray-300');
        });
    }
    // Reset reorder
    const reorderEl = document.getElementById('reorder-' + gameId);
    if (reorderEl) {
        reorderEl.querySelectorAll('.reorder-line').forEach(l => {
            l.classList.remove('border-green-500', 'bg-green-900/40', 'border-red-500');
        });
    }
    // Reset fix textarea
    const fixEl = document.getElementById('fix-' + gameId);
    if (fixEl) {
        fixEl.classList.remove('border-green-500', 'border-red-500');
    }
}

// Mark lesson as completed
function markLessonCompleted(index, animate = true) {
    const lessonId = lessonData[index].id;
    saveCompletedLesson(lessonId);

    // Update lesson header
    const numEl = document.getElementById('lessonNum-' + index);
    numEl.innerHTML = '✓';
    numEl.classList.remove('bg-[#2a2a2a]', 'text-gray-400');
    numEl.classList.add('bg-green-900/60', 'text-green-400');

    // Show badge
    document.getElementById('lessonBadge-' + index).classList.remove('hidden');

    // Update card border
    const card = document.getElementById('lesson-' + index);
    card.classList.add('border-green-800/50');

    if (animate) {
        // Hide game, show completed
        const gameEl = document.getElementById('lessonGame-' + index);
        if (gameEl) gameEl.classList.add('hidden');
        const completeEl = document.getElementById('lessonComplete-' + index);
        if (completeEl) {
            completeEl.classList.remove('hidden');
            completeEl.style.animation = 'fadeSlideIn 0.3s ease';
        }
        document.getElementById('lessonInfo-' + index).classList.add('hidden');
        updateProgressBar();
    }
}

// Update progress bar based on completed lessons
function updateProgressBar() {
    const completed = getCompletedLessons();
    const lessonsInModule = lessonData.map(l => l.id);
    const completedCount = lessonsInModule.filter(id => completed.includes(id)).length;
    const pct = totalLessons > 0 ? Math.round((completedCount / totalLessons) * 100) : 0;
    const bar = document.getElementById('progressBar');
    const text = document.getElementById('progressText');
    if (bar) bar.style.width = pct + '%';
    if (text) text.textContent = pct + '%';
}

// Game won handler
function onGameWon(index) {
    markLessonCompleted(index, true);
    showConfetti(document.getElementById('lesson-' + index));
}

// Game lost handler — auto-return to lesson info after a short delay
function onGameLost(index) {
    setTimeout(() => {
        backToInfo(index);
        // Inject a retry notification at the top of the info panel
        const infoEl = document.getElementById('lessonInfo-' + index);
        const existingNotif = document.getElementById('retryNotif-' + index);
        if (existingNotif) existingNotif.remove();
        const notif = document.createElement('div');
        notif.id = 'retryNotif-' + index;
        notif.className = 'mb-4 p-3 rounded-lg text-sm font-semibold bg-yellow-900/40 text-yellow-400 border border-yellow-800 flex items-center gap-2';
        notif.innerHTML = '📖 Not quite right — review the lesson content above, then try again!';
        infoEl.insertBefore(notif, infoEl.firstChild);
        setTimeout(() => notif.remove(), 6000);
    }, 1500);
}

// Check fill_blank / predict_output answer
function checkAnswer(btn, gameId, selected, correct, lessonIndex) {
    const result = document.getElementById('result-' + gameId);
    const options = btn.parentElement.querySelectorAll('.game-option');
    options.forEach(o => { o.disabled = true; o.classList.add('pointer-events-none', 'opacity-50'); });
    
    if (selected === correct) {
        btn.classList.remove('bg-[#2a2a2a]', 'border-[#444]', 'text-gray-300');
        btn.classList.add('bg-green-900/60', 'border-green-500', 'text-green-300');
        btn.classList.remove('opacity-50');
        result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-green-900/40 text-green-400 border border-green-800';
        result.innerHTML = '✅ Correct! Well done!';
        result.classList.remove('hidden');
        setTimeout(() => onGameWon(lessonIndex), 1200);
    } else {
        btn.classList.remove('bg-[#2a2a2a]', 'border-[#444]', 'text-gray-300');
        btn.classList.add('bg-red-900/60', 'border-red-500', 'text-red-300');
        btn.classList.remove('opacity-50');
        options.forEach(o => {
            if (o.textContent.trim() === correct) {
                o.classList.remove('bg-[#2a2a2a]', 'border-[#444]', 'text-gray-300', 'opacity-50');
                o.classList.add('bg-green-900/60', 'border-green-500', 'text-green-300');
            }
        });
        result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-red-900/40 text-red-400 border border-red-800';
        result.innerHTML = '❌ Not quite. The correct answer is: <span class="text-white">' + correct + '</span>';
        result.classList.remove('hidden');
        setTimeout(() => onGameLost(lessonIndex), 1000);
    }
}

// Match pairs logic
const matchState = {};
function selectMatchItem(btn, gameId, side, value, lessonIndex) {
    if (!matchState[gameId]) matchState[gameId] = { left: null, right: null, matched: 0, pairs: [] };
    const state = matchState[gameId];
    const pairs = lessonData[lessonIndex]?.game?.pairs || [];
    
    if (side === 'left') {
        document.querySelectorAll('#match-' + gameId + ' .match-left').forEach(b => b.classList.remove('border-white', 'text-white'));
        btn.classList.add('border-white', 'text-white');
        state.left = { el: btn, value: value };
    } else {
        document.querySelectorAll('#match-' + gameId + ' .match-right').forEach(b => b.classList.remove('border-white', 'text-white'));
        btn.classList.add('border-white', 'text-white');
        state.right = { el: btn, value: value };
    }
    
    if (state.left !== null && state.right !== null) {
        const leftIdx = state.left.value;
        const correctType = pairs[leftIdx] ? pairs[leftIdx][1] : null;
        
        if (correctType === state.right.value) {
            state.left.el.classList.add('bg-green-900/60', 'border-green-500', 'text-green-300');
            state.left.el.classList.add('pointer-events-none');
            state.right.el.classList.add('bg-green-900/60', 'border-green-500', 'text-green-300');
            state.right.el.classList.add('pointer-events-none');
            state.matched++;
            if (state.matched >= pairs.length) {
                const result = document.getElementById('result-' + gameId);
                result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-green-900/40 text-green-400 border border-green-800';
                result.innerHTML = '✅ All matched! Great job!';
                result.classList.remove('hidden');
                setTimeout(() => onGameWon(lessonIndex), 1200);
            }
        } else {
            state.left.el.classList.add('bg-red-900/60', 'border-red-500');
            state.right.el.classList.add('bg-red-900/60', 'border-red-500');
            setTimeout(() => {
                state.left.el.classList.remove('bg-red-900/60', 'border-red-500', 'border-white', 'text-white');
                state.right.el.classList.remove('bg-red-900/60', 'border-red-500', 'border-white', 'text-white');
            }, 800);
        }
        state.left = null;
        state.right = null;
    }
}

// Drag & drop for reorder
let draggedEl = null;
function dragStart(e) { draggedEl = e.target.closest('.reorder-line'); e.dataTransfer.effectAllowed = 'move'; }
function dragOver(e) { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; }
function dropLine(e) {
    e.preventDefault();
    const target = e.target.closest('.reorder-line');
    if (target && draggedEl && target !== draggedEl) {
        const container = target.parentElement;
        const items = [...container.children];
        const fromIdx = items.indexOf(draggedEl);
        const toIdx = items.indexOf(target);
        if (fromIdx < toIdx) { target.after(draggedEl); } else { target.before(draggedEl); }
    }
}
function checkReorder(gameId, correctOrder, lessonIndex) {
    const container = document.getElementById('reorder-' + gameId);
    const lines = [...container.querySelectorAll('.reorder-line')];
    const currentOrder = lines.map(l => l.getAttribute('data-original'));
    const result = document.getElementById('result-' + gameId);
    
    if (JSON.stringify(currentOrder) === JSON.stringify(correctOrder)) {
        lines.forEach(l => { l.classList.add('border-green-500', 'bg-green-900/40'); });
        result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-green-900/40 text-green-400 border border-green-800';
        result.innerHTML = '✅ Perfect order! Well done!';
        result.classList.remove('hidden');
        setTimeout(() => onGameWon(lessonIndex), 1200);
    } else {
        lines.forEach((l, i) => {
            if (l.getAttribute('data-original') === correctOrder[i]) {
                l.classList.add('border-green-500');
                l.classList.remove('border-red-500');
            } else {
                l.classList.add('border-red-500');
                l.classList.remove('border-green-500');
            }
        });
        result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-red-900/40 text-red-400 border border-red-800';
        result.innerHTML = '❌ Not quite right. Try rearranging the highlighted lines.';
        result.classList.remove('hidden');
        setTimeout(() => onGameLost(lessonIndex), 1000);
    }
}

// Fix bug game
function checkFix(gameId, correctAnswer, lessonIndex) {
    const textarea = document.getElementById('fix-' + gameId);
    const userAnswer = textarea.value.trim();
    const correct = correctAnswer.trim();
    const result = document.getElementById('result-' + gameId);
    const normalize = s => s.replace(/\s+/g, ' ').trim();
    
    if (normalize(userAnswer) === normalize(correct)) {
        textarea.classList.add('border-green-500', 'text-green-400');
        result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-green-900/40 text-green-400 border border-green-800';
        result.innerHTML = '✅ Bug fixed! Excellent!';
        result.classList.remove('hidden');
        setTimeout(() => onGameWon(lessonIndex), 1200);
    } else {
        textarea.classList.add('border-red-500');
        result.className = 'mt-3 p-3 rounded-lg text-sm font-semibold bg-red-900/40 text-red-400 border border-red-800';
        result.innerHTML = '❌ Not quite. Check the hint and try again.';
        result.classList.remove('hidden');
        setTimeout(() => {
            textarea.classList.remove('border-red-500');
            onGameLost(lessonIndex);
        }, 1000);
    }
}

// Mini confetti effect
function showConfetti(el) {
    const rect = el.getBoundingClientRect();
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `position:fixed;width:6px;height:6px;border-radius:50%;pointer-events:none;z-index:9999;
            left:${rect.left + rect.width/2 + (Math.random()-0.5)*150}px;
            top:${rect.top + (Math.random()-0.5)*40}px;
            background:${['#22c55e','#3b82f6','#eab308','#ef4444','#a855f7','#ffffff'][Math.floor(Math.random()*6)]};
            transition:all 1.2s ease;opacity:1;`;
        document.body.appendChild(particle);
        requestAnimationFrame(() => {
            particle.style.top = (parseFloat(particle.style.top) - 80 - Math.random()*60) + 'px';
            particle.style.opacity = '0';
        });
        setTimeout(() => particle.remove(), 1400);
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', initLessonStates);

// ─── Constants ──────────────────────────────────────────────────────────────────
const baseUrl   = '<?= $baseUrl ?>';
const moduleLang = '<?= $module['language'] ?>';

// ─── Prism overlay init for fix_bug textareas ────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.fix-bug-textarea').forEach(ta => {
        const gameId  = ta.id.replace('fix-', '');
        const pre     = document.getElementById('fixPre-'  + gameId);
        const codeEl  = document.getElementById('fixCode-' + gameId);
        if (!pre || !codeEl) return;

        function highlight() {
            const escaped = ta.value
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            codeEl.innerHTML = escaped + '\n';
            if (typeof Prism !== 'undefined') Prism.highlightElement(codeEl);
            pre.scrollTop  = ta.scrollTop;
            pre.scrollLeft = ta.scrollLeft;
        }

        highlight();
        ta.addEventListener('input', highlight);
        ta.addEventListener('scroll', () => {
            pre.scrollTop  = ta.scrollTop;
            pre.scrollLeft = ta.scrollLeft;
        });
        ta.addEventListener('keydown', e => {
            if (e.key === 'Tab') {
                e.preventDefault();
                const s = ta.selectionStart;
                ta.value = ta.value.substring(0, s) + '    ' + ta.value.substring(ta.selectionEnd);
                ta.selectionStart = ta.selectionEnd = s + 4;
                highlight();
            }
        });
    });
});

// ─── Local run for fix_bug ──────────────────────────────────────────────────────
async function runFix(gameId) {
    const ta       = document.getElementById('fix-' + gameId);
    const outputEl = document.getElementById('fixOutput-' + gameId);
    if (!ta || !outputEl) return;

    outputEl.classList.remove('hidden');
    outputEl.innerHTML = '<span class="text-yellow-400 animate-pulse">▶ Running…</span>';

    try {
        const res  = await fetch(baseUrl + '/code/run', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ code: ta.value, language: moduleLang, stdin: '' })
        });
        const data = await res.json();
        const statusLabel = data.status
            ? ` [${data.status}${data.time ? ' · ' + data.time + 's' : ''}]`
            : '';

        if (data.error && data.status !== 'Accepted') {
            outputEl.innerHTML =
                `<span class="text-red-400">✗ ${escapeHtml(data.status)}${statusLabel}:</span>\n` +
                `<span class="text-red-300">${escapeHtml(data.error || data.output)}</span>`;
        } else {
            outputEl.innerHTML =
                `<span class="text-green-400">✓ Output${statusLabel}:</span>\n` +
                `<span class="text-white">${escapeHtml(data.output || '(no output)')}</span>`;
        }
    } catch (err) {
        outputEl.innerHTML = `<span class="text-red-400">Connection error: ${escapeHtml(err.message)}</span>`;
    }
}

function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = String(text ?? '');
    return d.innerHTML;
}
</script>

<style>
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.lesson-content-text p { margin-bottom: 0.5rem; }
.lesson-content-text p:last-child { margin-bottom: 0; }
.lesson-card.border-green-800\/50 { border-color: rgba(22, 101, 52, 0.5); }

/* Ensure example code blocks on module pages use a dark grey background */
pre.bg-black, pre.bg-black code, pre[class*="language-"] {
    background-color: #333333 !important;
    color: #fff !important;
}

/* Make sure inline code inside pre doesn't override background */
pre[class*="language-"] code { background: transparent !important; }
</style>