<!-- Challenge Play View - Code Editor & Submission Interface -->
<?php
$langInfo = $langConfig[$challenge['language']] ?? ['name' => ucfirst($challenge['language']), 'icon' => '📝'];
$isCodeChallenge = $challenge['type'] === 'code';

// Map app language slugs → Prism.js grammar class names
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
$prismLang = $prismLangMap[$challenge['language']] ?? 'clike';
?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-gray-400 mb-6 font-bold" style="text-shadow: 1px 1px 0px #000;">
    <a href="<?= $baseUrl ?>/challenges" class="hover:text-white transition">Challenges</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(1px 1px 0px #000);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    <span class="text-white"><?= htmlspecialchars($challenge['title']) ?></span>
</nav>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Left Panel: Challenge Info -->
    <div>
        <div class="bg-surface border-4 border-black rounded-none p-6 mb-4" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl" style="filter: drop-shadow(2px 2px 0px #000);"><?= $langInfo['icon'] ?></span>
                    <h1 class="text-xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= htmlspecialchars($challenge['title']) ?></h1>
                </div>
                <?php if ($isCompleted): ?>
                <span class="px-2 py-1 text-xs font-bold rounded-none bg-green-500 text-black border-2 border-black" style="box-shadow: 2px 2px 0px #000;">
                    ✓ Completed
                </span>
                <?php else: ?>
                <span class="px-2 py-1 text-xs font-bold rounded-none bg-white text-black border-2 border-black" style="box-shadow: 2px 2px 0px #000;">
                    +<?= $challenge['xp_reward'] ?> XP
                </span>
                <?php endif; ?>
            </div>

            <p class="text-gray-300 mb-4 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($challenge['description']) ?></p>

            <div class="flex items-center gap-3 mb-4 text-xs font-bold" style="text-shadow: 1px 1px 0px #000;">
                <span class="px-2 py-1 rounded-none bg-black text-white border-2 border-gray-600" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #555;"><?= ucfirst($challenge['difficulty']) ?></span>
                <span class="px-2 py-1 rounded-none bg-black text-white border-2 border-gray-600 lang-label" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #555;"><?= $langInfo['name'] ?></span>
                <?php if (!empty($challenge['time_limit'])): ?>
                <span class="px-2 py-1 rounded-none bg-black text-white border-2 border-gray-600" id="timer" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #555;">
                    ⏱ <?= gmdate('i:s', $challenge['time_limit']) ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Instructions -->
            <div class="bg-black border-4 border-black rounded-none p-4" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2" style="text-shadow: 1px 1px 0px #000;">Instructions</h3>
                <p class="text-sm text-white font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($challenge['instructions']) ?></p>
            </div>
        </div>

        <!-- Hints (collapsible) -->
        <?php if (!empty($challenge['hints'])): ?>
        <div class="bg-surface border-4 border-black rounded-none p-4 mb-4" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <button onclick="document.getElementById('hints').classList.toggle('hidden')"
                    class="flex items-center justify-between w-full text-sm font-bold text-gray-300 hover:text-white transition" style="text-shadow: 1px 1px 0px #000;">
                <span>💡 Hints (<?= count($challenge['hints']) ?>)</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(1px 1px 0px #000);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="hints" class="hidden mt-3 space-y-2">
                <?php foreach ($challenge['hints'] as $i => $hint): ?>
                <p class="text-sm text-gray-300 pl-4 border-l-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($hint) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Previous Attempts -->
        <?php if (!empty($attempts)): ?>
        <div class="bg-surface border-4 border-black rounded-none p-4" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold text-gray-300 mb-3" style="text-shadow: 1px 1px 0px #000;">Previous Attempts (<?= count($attempts) ?>)</h3>
            <div class="space-y-2 max-h-40 overflow-y-auto">
                <?php foreach (array_reverse(array_slice($attempts, -5)) as $attempt): ?>
                <div class="flex items-center justify-between text-xs py-2 border-b-4 border-black last:border-0 font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <span class="<?= $attempt['success'] ? 'text-green-400' : 'text-red-400' ?>">
                        <?= $attempt['success'] ? '✓ Passed' : '✗ Failed' ?>
                    </span>
                    <span class="text-gray-300"><?= $attempt['time_taken'] ?>s • <?= $attempt['xp_earned'] ?> XP</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Panel: Code Editor / Multiple Choice -->
    <div>
        <?php if ($isCodeChallenge): ?>
        <!-- Code Editor -->
        <div class="bg-surface border-4 border-black rounded-none overflow-hidden" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <!-- Editor Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b-4 border-black bg-black" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-none bg-red-500 border-2 border-black"></div>
                    <div class="w-3 h-3 rounded-none bg-yellow-500 border-2 border-black"></div>
                    <div class="w-3 h-3 rounded-none bg-green-500 border-2 border-black"></div>
                    <span class="ml-3 text-xs text-white font-mono font-bold lang-label" style="text-shadow: 1px 1px 0px #000;"><?= $langInfo['name'] ?></span>
                </div>
                <button onclick="resetCode()" class="text-xs text-gray-300 hover:text-white transition font-bold" style="text-shadow: 1px 1px 0px #000;">Reset</button>
            </div>

            <!-- Prism.js Overlay Code Editor -->
            <div class="prism-editor-wrap bg-gray-900" style="height:16rem; box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;">
                <pre id="editorPre" aria-hidden="true" style="height:16rem;overflow:auto;padding:1rem;"><code id="editorCode" class="language-<?= $prismLang ?>"></code></pre>
                <textarea id="codeEditor"
                          class="bg-transparent text-white font-mono"
                          style="height:16rem;"
                          spellcheck="false"
                          autocomplete="off"
                          autocorrect="off"
                          autocapitalize="off"
                          placeholder="Write your code here…"></textarea>
            </div>

            <!-- Editor Footer -->
            <div class="flex items-center justify-between px-4 py-3 border-t-4 border-black bg-black" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                <span class="text-xs text-gray-400 font-bold" id="charCount" style="text-shadow: 1px 1px 0px #000;">0 chars</span>
                <div class="flex gap-2">
                    <button onclick="runCode()"
                            class="px-4 py-2 text-sm bg-gray-800 text-white border-4 border-black rounded-none hover:bg-gray-700 transition flex items-center gap-2 font-bold" style="box-shadow: inset 2px 2px 0px #aaa, inset -2px -2px 0px #444; text-shadow: 1px 1px 0px #000;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(1px 1px 0px #000);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                        Run
                    </button>
                    <button onclick="submitCode()"
                            id="submitBtn"
                            class="px-4 py-2 text-sm bg-white text-black border-4 border-black font-bold rounded-none hover:bg-gray-200 transition flex items-center gap-2" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc, 2px 2px 0px #000;">
                        Submit
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Output Console -->
        <div class="mt-4 bg-surface border-4 border-black rounded-none overflow-hidden" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <div class="px-4 py-2 border-b-4 border-black bg-black" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000;">
                <span class="text-xs text-white font-mono font-bold" style="text-shadow: 1px 1px 0px #000;">Output</span>
            </div>
            <div id="outputConsole" class="h-32 p-4 font-mono text-sm overflow-y-auto text-gray-300 bg-gray-900 font-bold" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333; text-shadow: 1px 1px 0px #000;">
                <span class="text-gray-500">// Output will appear here...</span>
            </div>
        </div>

        <?php else: ?>
        <!-- Multiple Choice -->
        <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4" style="text-shadow: 1px 1px 0px #000;">Select Your Answer</h3>

            <div class="space-y-3" id="mcOptions">
                <?php foreach ($challenge['options'] as $i => $option): ?>
                <button onclick="selectOption(this, '<?= htmlspecialchars(addslashes($option)) ?>')"
                        class="mc-option w-full text-left px-4 py-3 bg-black border-4 border-black rounded-none text-sm hover:bg-gray-800 transition flex items-center gap-3 font-bold text-white" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
                    <span class="w-6 h-6 rounded-none border-2 border-gray-600 flex items-center justify-center text-xs flex-shrink-0 bg-gray-900" style="box-shadow: inset 2px 2px 0px #000, inset -2px -2px 0px #333;">
                        <?= chr(65 + $i) ?>
                    </span>
                    <span><?= htmlspecialchars($option) ?></span>
                </button>
                <?php endforeach; ?>
            </div>

            <button onclick="submitMC()"
                    id="submitMCBtn"
                    disabled
                    class="mt-6 w-full py-3 bg-white text-black border-4 border-black font-bold rounded-none hover:bg-gray-200 transition disabled:opacity-50 disabled:cursor-not-allowed" style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc, 4px 4px 0px #000;">
                Submit Answer
            </button>
        </div>
        <?php endif; ?>

        <!-- Result Modal -->
        <div id="resultModal" class="hidden mt-4 bg-surface border-4 border-black rounded-none p-6 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
            <div id="resultIcon" class="text-4xl mb-3" style="filter: drop-shadow(2px 2px 0px #000);"></div>
            <h3 id="resultTitle" class="text-lg font-bold mb-2 text-white" style="text-shadow: 2px 2px 0px #000;"></h3>
            <p id="resultMessage" class="text-sm text-gray-300 mb-4 font-bold" style="text-shadow: 1px 1px 0px #000;"></p>
            <div id="resultXP" class="hidden mb-4">
                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white text-black border-4 border-black text-sm font-bold rounded-none" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc, 2px 2px 0px #000;">
                    ⚡ +<span id="xpAmount">0</span> XP
                </span>
            </div>
            <div id="levelUpBanner" class="hidden mb-4 px-4 py-3 bg-yellow-500 border-4 border-black text-black font-bold rounded-none" style="box-shadow: inset 4px 4px 0px #fde047, inset -4px -4px 0px #a16207;">
                <span style="filter: drop-shadow(2px 2px 0px #000);">🎉</span> Level Up! You reached Level <span id="newLevelNum"></span>!
            </div>
            <a href="<?= $baseUrl ?>/challenges" class="text-sm text-gray-300 hover:text-white transition font-bold" style="text-shadow: 1px 1px 0px #000;">
                ← Back to Challenges
            </a>
        </div>
    </div>
</div>

<!-- Challenge-specific JavaScript -->
<script>
const challengeId   = '<?= $challenge['id'] ?>';
const baseUrl       = '<?= $baseUrl ?>';
const starterCode   = <?= json_encode($challenge['starter_code'] ?? '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const expectedOutput = <?= json_encode($challenge['expected_output'] ?? '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const challengeType = '<?= $challenge['type'] ?>';
const challengeLang = '<?= $challenge['language'] ?>';
let startTime     = Date.now();
let selectedAnswer = null;
let timeLimit     = <?= $challenge['time_limit'] ?? 0 ?>;
let lastRunOutput = ''; // stores output from most recent run

// ─── Timer ────────────────────────────────────────────────────────────────────
<?php if (!empty($challenge['time_limit'])): ?>
let remaining = timeLimit;
const timerInterval = setInterval(() => {
    remaining--;
    if (remaining <= 0) {
        clearInterval(timerInterval);
        document.getElementById('timer').textContent = '⏱ 00:00';
        document.getElementById('timer').classList.add('text-red-400');
    } else {
        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        document.getElementById('timer').textContent = `⏱ ${m}:${s}`;
    }
}, 1000);
<?php endif; ?>

// ─── Prism.js Code Editor ─────────────────────────────────────────────────────
const editor     = document.getElementById('codeEditor');
const editorPre  = document.getElementById('editorPre');
const editorCode = document.getElementById('editorCode');

function syncScroll() {
    if (!editorPre || !editor) return;
    editorPre.scrollTop  = editor.scrollTop;
    editorPre.scrollLeft = editor.scrollLeft;
}

function highlightEditor() {
    if (!editorCode || !editor) return;
    // Must escape HTML entities before inserting into <code>
    const escaped = editor.value
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    editorCode.innerHTML = escaped + '\n'; // trailing \n fixes last-line cursor
    if (typeof Prism !== 'undefined') {
        Prism.highlightElement(editorCode);
    }
    syncScroll();
}

if (editor) {
    editor.value = starterCode;
    highlightEditor();
    document.getElementById('charCount').textContent = editor.value.length + ' chars';

    editor.addEventListener('input', () => {
        highlightEditor();
        document.getElementById('charCount').textContent = editor.value.length + ' chars';
    });

    editor.addEventListener('scroll', syncScroll);

    // Tab key → 4 spaces
    editor.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            e.preventDefault();
            const s = editor.selectionStart;
            editor.value = editor.value.substring(0, s) + '    ' + editor.value.substring(editor.selectionEnd);
            editor.selectionStart = editor.selectionEnd = s + 4;
            highlightEditor();
        }
    });
}

function resetCode() {
    if (editor) {
        editor.value = starterCode;
        highlightEditor();
        document.getElementById('charCount').textContent = editor.value.length + ' chars';
    }
    document.getElementById('outputConsole').innerHTML =
        '<span class="text-gray-600">// Output will appear here...</span>';
    lastRunOutput = '';
}

// ─── Local Code Execution ───────────────────────────────────────────────────────
function runCode() {
    const outputEl = document.getElementById('outputConsole');
    const runBtn   = document.querySelector('button[onclick="runCode()"]');
    const code     = editor ? editor.value : '';

    outputEl.innerHTML = '<span class="text-yellow-400 animate-pulse">▶ Running…</span>';
    if (runBtn) { runBtn.disabled = true; runBtn.textContent = 'Running…'; }

    fetch(`${baseUrl}/challenges/${challengeId}/run`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code, language: challengeLang, stdin: '' })
    })
    .then(r => r.json())
    .then(result => {
        if (runBtn) {
            runBtn.disabled = false;
            runBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg> Run';
        }
        lastRunOutput = result.output ?? '';

        const statusLabel = result.status
            ? `<span class="text-gray-500 text-xs ml-2">[${result.status}${result.time ? ' · ' + result.time + 's' : ''}${result.memory ? ' · ' + Math.round(result.memory) + ' KB' : ''}]</span>`
            : '';

        if (result.error && result.status !== 'Accepted') {
            outputEl.innerHTML =
                `<span class="text-red-400">✗ ${escapeHtml(result.status)}:${statusLabel}</span>\n` +
                `<span class="text-red-300">${escapeHtml(result.error || result.output)}</span>`;
        } else if (lastRunOutput !== '') {
            outputEl.innerHTML =
                `<span class="text-green-400">✓ Output:${statusLabel}</span>\n` +
                `<span class="text-white">${escapeHtml(lastRunOutput)}</span>`;
        } else {
            outputEl.innerHTML = `<span class="text-gray-400">(no output)${statusLabel}</span>`;
        }
    })
    .catch(err => {
        if (runBtn) {
            runBtn.disabled = false;
            runBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg> Run';
        }
        outputEl.innerHTML = `<span class="text-red-400">Connection error: ${escapeHtml(err.message)}</span>`;
    });
}

function submitCode() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = 'Submitting…';

    const code      = editor ? editor.value : '';
    const timeTaken = Math.round((Date.now() - startTime) / 1000);
    // Submit with last known execution output (or empty if never run)
    const output = lastRunOutput;

    fetch(`${baseUrl}/challenges/${challengeId}/submit`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code, output, time_taken: timeTaken })
    })
    .then(r => r.json())
    .then(result => {
        showResult(result);
        btn.disabled = false;
        btn.innerHTML = 'Submit <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = 'Submit';
        alert('Submission error: ' + err.message);
    });
}

// ─── Multiple Choice ──────────────────────────────────────────────────────────
function selectOption(el, answer) {
    document.querySelectorAll('.mc-option').forEach(opt => {
        opt.classList.remove('border-white', 'bg-surface-light');
        opt.classList.add('border-border');
    });
    el.classList.remove('border-border');
    el.classList.add('border-white', 'bg-surface-light');
    selectedAnswer = answer;
    document.getElementById('submitMCBtn').disabled = false;
}

function submitMC() {
    if (!selectedAnswer) return;

    const btn = document.getElementById('submitMCBtn');
    btn.disabled = true;
    btn.textContent = 'Submitting…';

    const timeTaken = Math.round((Date.now() - startTime) / 1000);

    fetch(`${baseUrl}/challenges/${challengeId}/submit`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code: '', output: selectedAnswer, time_taken: timeTaken })
    })
    .then(r => r.json())
    .then(result => {
        showResult(result);
        btn.disabled = false;
        btn.textContent = 'Submit Answer';
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = 'Submit Answer';
    });
}

// ─── Result Modal ─────────────────────────────────────────────────────────────
function showResult(result) {
    const modal = document.getElementById('resultModal');
    modal.classList.remove('hidden');

    document.getElementById('resultIcon').textContent    = result.success ? '🎉' : '😅';
    document.getElementById('resultTitle').textContent   = result.success ? 'Challenge Passed!' : 'Not Quite Right';
    document.getElementById('resultMessage').textContent = result.message;

    if (result.xp > 0) {
        document.getElementById('resultXP').classList.remove('hidden');
        document.getElementById('xpAmount').textContent = result.xp;
    }

    if (result.level_up) {
        document.getElementById('levelUpBanner').classList.remove('hidden');
        document.getElementById('newLevelNum').textContent = result.new_level;
    }

    modal.scrollIntoView({ behavior: 'smooth' });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = String(text ?? '');
    return div.innerHTML;
}
</script>
