<!-- Admin: Edit Module -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Edit Module</h1>
        <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($editModule['icon'] ?? '') ?> <?= htmlspecialchars($editModule['title']) ?></p>
    </div>
    <a href="<?= $baseUrl ?>/admin/modules" class="px-4 py-2 text-sm bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
        ← Back to Modules
    </a>
</div>

<form method="POST" action="<?= $baseUrl ?>/admin/modules/<?= $editModule['id'] ?>" class="max-w-3xl">
    <div class="bg-surface border-4 border-black rounded-none p-6 space-y-5" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($editModule['title']) ?>" required
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Language</label>
                <input type="text" name="language" value="<?= htmlspecialchars($editModule['language'] ?? '') ?>"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Description</label>
            <textarea name="description" rows="2"
                class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars($editModule['description'] ?? '') ?></textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Icon (emoji)</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($editModule['icon'] ?? '') ?>" maxlength="4"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty</label>
                <select name="difficulty" class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <option value="beginner" <?= ($editModule['difficulty'] ?? '') === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="intermediate" <?= ($editModule['difficulty'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="advanced" <?= ($editModule['difficulty'] ?? '') === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Order</label>
                <input type="number" name="order" value="<?= $editModule['order'] ?? 1 ?>" min="1"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">
                Lessons JSON
                <span class="text-gray-400">(<?= count($editModule['lessons'] ?? []) ?> lessons)</span>
            </label>
            <textarea name="lessons_json" rows="12"
                class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"><?= htmlspecialchars(json_encode($editModule['lessons'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></textarea>
            <p class="text-xs text-gray-400 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Edit the JSON array directly. Each lesson: {"title", "content", "xp", "game": {...}}</p>
        </div>

        <div class="bg-black border-4 border-black rounded-none p-4 text-xs text-gray-300 space-y-1 font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
            <p><strong class="text-white">ID:</strong> <?= $editModule['id'] ?></p>
            <p><strong class="text-white">Total XP:</strong> <?= $editModule['total_xp'] ?? 0 ?></p>
            <p><strong class="text-white">Challenges:</strong> <?= count($editModule['challenges'] ?? []) ?></p>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-white text-black font-bold border-4 border-black rounded-none hover:bg-gray-200 transition" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;">
                Save Module
            </button>
            <a href="<?= $baseUrl ?>/admin/modules" class="px-6 py-2.5 bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
                Cancel
            </a>
        </div>
    </div>
</form>
