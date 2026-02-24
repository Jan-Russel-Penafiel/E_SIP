<!-- Admin: Challenges Management -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Manage Challenges</h1>
        <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= count($challenges) ?> challenges total</p>
    </div>
    <div class="flex gap-3">
        <a href="<?= $baseUrl ?>/admin" class="px-4 py-2 text-sm bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">← Admin</a>
    </div>
</div>

<!-- Create Challenge -->
<div class="bg-surface border-4 border-black rounded-none p-5 mb-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <h2 class="text-sm font-bold text-white uppercase tracking-wider mb-4" style="text-shadow: 1px 1px 0px #000;">Create New Challenge</h2>
    <form method="POST" action="<?= $baseUrl ?>/admin/challenges/create">
        <div class="grid md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Title</label>
                <input type="text" name="title" required placeholder="Challenge Title"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Language</label>
                <select name="language" class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <option value="python">Python</option>
                    <option value="javascript">JavaScript</option>
                    <option value="java">Java</option>
                    <option value="php">PHP</option>
                </select>
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
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Type</label>
                <select name="type" class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <option value="code">Code</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="fill_blank">Fill Blank</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">XP Reward</label>
                <input type="number" name="xp_reward" value="50" min="1"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Time Limit (sec)</label>
                <input type="number" name="time_limit" value="300" min="30"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Instructions</label>
                <textarea name="instructions" rows="2" placeholder="What to do..."
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Description</label>
                <textarea name="description" rows="2" placeholder="Brief description"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"></textarea>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Starter Code</label>
                <textarea name="starter_code" rows="3" placeholder="# Your code here"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Expected Output</label>
                <textarea name="expected_output" rows="3" placeholder="Hello World"
                    class="w-full bg-black border-4 border-black rounded-none px-3 py-2 text-sm text-white font-mono focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;"></textarea>
            </div>
        </div>
        <button type="submit" class="px-5 py-2 bg-white text-black font-bold border-4 border-black rounded-none hover:bg-gray-200 transition text-sm" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;">
            Create Challenge
        </button>
    </form>
</div>

<!-- Challenges Table -->
<div class="bg-surface border-4 border-black rounded-none overflow-hidden" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-black border-b-4 border-black">
                <tr>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Title</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Language</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Type</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">XP</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Time</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Module</th>
                    <th class="px-4 py-3 text-xs text-gray-300 uppercase font-bold" style="text-shadow: 1px 1px 0px #000;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y-4 divide-black">
                <?php foreach ($challenges as $challenge): ?>
                <tr class="hover:bg-gray-800 transition font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <td class="px-4 py-3 text-white"><?= htmlspecialchars($challenge['title']) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 bg-gray-700 text-white border-2 border-black rounded-none text-xs" style="box-shadow: inset 1px 1px 0px #9ca3af, inset -1px -1px 0px #374151;"><?= htmlspecialchars($challenge['language'] ?? '-') ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                        $diffColors = ['beginner' => 'text-green-400', 'intermediate' => 'text-yellow-400', 'advanced' => 'text-red-400'];
                        $dc = $diffColors[$challenge['difficulty'] ?? ''] ?? 'text-gray-300';
                        ?>
                        <span class="<?= $dc ?> text-xs font-bold"><?= ucfirst($challenge['difficulty'] ?? '-') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-xs"><?= $challenge['type'] ?? '-' ?></td>
                    <td class="px-4 py-3 text-yellow-400 font-bold"><?= $challenge['xp_reward'] ?? 0 ?></td>
                    <td class="px-4 py-3 text-gray-300"><?= ($challenge['time_limit'] ?? 0) ?>s</td>
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= htmlspecialchars($challenge['module_id'] ?? '-') ?></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="<?= $baseUrl ?>/admin/challenges/<?= $challenge['id'] ?>"
                                class="px-3 py-1 text-xs bg-blue-500 text-white border-2 border-black rounded-none hover:bg-blue-600 transition font-bold" style="box-shadow: inset 2px 2px 0px #93c5fd, inset -2px -2px 0px #1e3a8a; text-shadow: 1px 1px 0px #000;">Edit</a>
                            <form method="POST" action="<?= $baseUrl ?>/admin/challenges/<?= $challenge['id'] ?>/delete"
                                onsubmit="return confirm('Delete: <?= htmlspecialchars($challenge['title']) ?>?')">
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
