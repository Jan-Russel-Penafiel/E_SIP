<!-- Admin: Edit Challenge -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Edit Challenge</h1>
        <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($editChallenge['title']) ?></p>
    </div>
    <a href="<?= $baseUrl ?>/admin/challenges" class="px-4 py-2 text-sm bg-surface text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
        ← Back to Challenges
    </a>
</div>

<form method="POST" action="<?= $baseUrl ?>/admin/challenges/<?= $editChallenge['id'] ?>" class="max-w-3xl">
    <div class="bg-surface border-4 border-black rounded-none p-6 space-y-5" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <!-- Title & Language -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($editChallenge['title']) ?>" required
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Language</label>
                <select name="language" class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <?php foreach (['python', 'javascript', 'java', 'php'] as $lang): ?>
                    <option value="<?= $lang ?>" <?= ($editChallenge['language'] ?? '') === $lang ? 'selected' : '' ?>><?= ucfirst($lang) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Description</label>
            <textarea name="description" rows="2"
                class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars($editChallenge['description'] ?? '') ?></textarea>
        </div>

        <!-- Difficulty, Type, XP, Time -->
        <div class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty</label>
                <select name="difficulty" class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <?php foreach (['beginner', 'intermediate', 'advanced'] as $d): ?>
                    <option value="<?= $d ?>" <?= ($editChallenge['difficulty'] ?? '') === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Type</label>
                <select name="type" class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <?php foreach (['code', 'multiple_choice', 'fill_blank'] as $t): ?>
                    <option value="<?= $t ?>" <?= ($editChallenge['type'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $t)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">XP Reward</label>
                <input type="number" name="xp_reward" value="<?= $editChallenge['xp_reward'] ?? 50 ?>" min="1"
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Time Limit (sec)</label>
                <input type="number" name="time_limit" value="<?= $editChallenge['time_limit'] ?? 300 ?>" min="30"
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>

        <!-- Instructions -->
        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Instructions</label>
            <textarea name="instructions" rows="3"
                class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars($editChallenge['instructions'] ?? '') ?></textarea>
        </div>

        <!-- Expected Output -->
        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Expected Output</label>
            <textarea name="expected_output" rows="2"
                class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars($editChallenge['expected_output'] ?? '') ?></textarea>
        </div>

        <!-- Starter Code & Solution -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Starter Code</label>
                <textarea name="starter_code" rows="6"
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars($editChallenge['starter_code'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Solution</label>
                <textarea name="solution" rows="6"
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars($editChallenge['solution'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- JSON Fields -->
        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Test Cases JSON</label>
            <textarea name="test_cases_json" rows="4"
                class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars(json_encode($editChallenge['test_cases'] ?? [], JSON_PRETTY_PRINT)) ?></textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Options JSON <span class="text-gray-400">(multiple choice)</span></label>
                <textarea name="options_json" rows="4"
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars(json_encode($editChallenge['options'] ?? [], JSON_PRETTY_PRINT)) ?></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Hints JSON</label>
                <textarea name="hints_json" rows="4"
                    class="w-full bg-surface border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars(json_encode($editChallenge['hints'] ?? [], JSON_PRETTY_PRINT)) ?></textarea>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-surface border-4 border-black rounded-none p-4 text-xs text-gray-300 space-y-1 font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            <p style="text-shadow: 1px 1px 0px #000;"><strong class="text-white">ID:</strong> <?= $editChallenge['id'] ?></p>
            <p style="text-shadow: 1px 1px 0px #000;"><strong class="text-white">Module:</strong> <?= $editChallenge['module_id'] ?? 'None' ?></p>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-white text-black font-bold border-4 border-black rounded-none hover:bg-gray-200 transition" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc; text-shadow: 1px 1px 0px #fff;">
                Save Challenge
            </button>
            <a href="<?= $baseUrl ?>/admin/challenges" class="px-6 py-2.5 bg-surface text-white font-bold border-4 border-black rounded-none hover:bg-gray-800 transition" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
                Cancel
            </a>
        </div>
    </div>
</form>
