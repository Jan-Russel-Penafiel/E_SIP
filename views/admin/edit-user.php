<!-- Admin: Edit User -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Edit User</h1>
        <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($editUser['username']) ?> — <?= htmlspecialchars($editUser['email']) ?></p>
    </div>
    <a href="<?= $baseUrl ?>/admin/users" class="px-4 py-2 text-sm bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
        ← Back to Users
    </a>
</div>

<form method="POST" action="<?= $baseUrl ?>/admin/users/<?= $editUser['id'] ?>" class="max-w-2xl">
    <div class="bg-surface border-4 border-black rounded-none p-6 space-y-5" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($editUser['username']) ?>"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($editUser['email']) ?>"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Role</label>
                <select name="role" class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <option value="learner" <?= ($editUser['role'] ?? '') === 'learner' ? 'selected' : '' ?>>Learner</option>
                    <option value="admin" <?= ($editUser['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Difficulty</label>
                <select name="difficulty" class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
                    <option value="beginner" <?= ($editUser['difficulty'] ?? '') === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="intermediate" <?= ($editUser['difficulty'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="advanced" <?= ($editUser['difficulty'] ?? '') === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Level</label>
                <input type="number" name="level" value="<?= $editUser['level'] ?? 1 ?>" min="1"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">XP</label>
                <input type="number" name="xp" value="<?= $editUser['xp'] ?? 0 ?>" min="0"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
            <div>
                <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">Streak</label>
                <input type="number" name="streak" value="<?= $editUser['streak'] ?? 0 ?>" min="0"
                    class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-300 mb-1 uppercase tracking-wider font-bold" style="text-shadow: 1px 1px 0px #000;">New Password (leave blank to keep current)</label>
            <input type="password" name="password" placeholder="••••••••"
                class="w-full bg-black border-4 border-black rounded-none px-4 py-2.5 text-sm text-white focus:border-white focus:outline-none font-bold" style="box-shadow: inset 2px 2px 0px #000;">
        </div>

        <!-- Info -->
        <div class="bg-black border-4 border-black rounded-none p-4 text-xs text-gray-300 space-y-1 font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
            <p><strong class="text-white">ID:</strong> <?= $editUser['id'] ?></p>
            <p><strong class="text-white">Badges:</strong> <?= implode(', ', $editUser['badges'] ?? []) ?: 'None' ?></p>
            <p><strong class="text-white">Completed Challenges:</strong> <?= count($editUser['completed_challenges'] ?? []) ?></p>
            <p><strong class="text-white">Completed Modules:</strong> <?= count($editUser['completed_modules'] ?? []) ?></p>
            <p><strong class="text-white">Created:</strong> <?= $editUser['created_at'] ?? '-' ?></p>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-white text-black font-bold border-4 border-black rounded-none hover:bg-gray-200 transition" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;">
                Save Changes
            </button>
            <a href="<?= $baseUrl ?>/admin/users" class="px-6 py-2.5 bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
                Cancel
            </a>
        </div>
    </div>
</form>
