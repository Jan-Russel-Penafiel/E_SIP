<!-- Admin: Modules Management -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Manage Modules</h1>
        <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= count($modules) ?> modules total</p>
    </div>
    <div class="flex gap-3">
        <a href="<?= $baseUrl ?>/admin" class="px-4 py-2 text-sm bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">← Admin</a>
    </div>
</div>

<!-- Create Module -->
<div class="bg-surface border-4 border-black rounded-none p-5 mb-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <h2 class="text-sm font-bold text-white uppercase tracking-wider mb-4" style="text-shadow: 1px 1px 0px #000;">Create New Module</h2>
    <form method="POST" action="<?= $baseUrl ?>/admin/modules/create">
        <div class="grid md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Title</label>
                <input type="text" name="title" required placeholder="Module Title"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Language</label>
                <input type="text" name="language" placeholder="python" required
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty</label>
                <select name="difficulty" class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Icon (emoji)</label>
                <input type="text" name="icon" placeholder="🐍" maxlength="4"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Order</label>
                <input type="number" name="order" value="<?= count($modules) + 1 ?>" min="1"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Description</label>
                <input type="text" name="description" placeholder="Short description"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Lessons JSON (array)</label>
            <textarea name="lessons_json" rows="3" placeholder='[{"title":"Lesson 1","content":"Content here","xp":10}]'
                class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"></textarea>
        </div>
        <button type="submit" class="px-5 py-2 bg-white text-black font-bold border-4 border-black rounded-none hover:bg-gray-200 transition text-sm" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;">
            Create Module
        </button>
    </form>
</div>

<!-- Modules Table -->
<div class="bg-surface border-4 border-black rounded-none overflow-hidden" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-black border-b-4 border-black">
                <tr>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Order</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Icon</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Title</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Language</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Lessons</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">XP</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y-4 divide-black">
                <?php foreach ($modules as $module): ?>
                <tr class="hover:bg-gray-800 transition font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <td class="px-4 py-3 text-gray-300"><?= $module['order'] ?? '-' ?></td>
                    <td class="px-4 py-3 text-xl" style="filter: drop-shadow(2px 2px 0px #000);"><?= $module['icon'] ?? '📦' ?></td>
                    <td class="px-4 py-3 text-white"><?= htmlspecialchars($module['title']) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 bg-gray-700 text-white border-2 border-black rounded-none text-xs lang-label" style="box-shadow: inset 1px 1px 0px #9ca3af, inset -1px -1px 0px #374151;"><?= htmlspecialchars($module['language'] ?? '-') ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                        $diffColors = ['beginner' => 'text-green-400', 'intermediate' => 'text-yellow-400', 'advanced' => 'text-red-400'];
                        $dc = $diffColors[$module['difficulty'] ?? ''] ?? 'text-gray-300';
                        ?>
                        <span class="<?= $dc ?> text-xs font-bold"><?= ucfirst($module['difficulty'] ?? '-') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-300"><?= count($module['lessons'] ?? []) ?></td>
                    <td class="px-4 py-3 text-yellow-400 font-bold"><?= $module['total_xp'] ?? 0 ?></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="<?= $baseUrl ?>/admin/modules/<?= $module['id'] ?>"
                                class="px-3 py-1 text-xs bg-blue-500 text-white border-2 border-black rounded-none hover:bg-blue-600 transition font-bold" style="box-shadow: inset 2px 2px 0px #93c5fd, inset -2px -2px 0px #1e3a8a; text-shadow: 1px 1px 0px #000;">Edit</a>
                            <form method="POST" action="<?= $baseUrl ?>/admin/modules/<?= $module['id'] ?>/delete"
                                onsubmit="return confirm('Delete module: <?= htmlspecialchars($module['title']) ?>?')">
                                <button type="submit" class="px-3 py-1 text-xs bg-red-500 text-white border-2 border-black rounded-none hover:bg-red-600 transition font-bold" style="box-shadow: inset 2px 2px 0px #fca5a5, inset -2px -2px 0px #991b1b; text-shadow: 1px 1px 0px #000;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
