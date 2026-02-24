<!-- Admin: Manage Users -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Manage Users</h1>
        <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;"><?= count($users) ?> registered users</p>
    </div>
    <a href="<?= $baseUrl ?>/admin" class="px-4 py-2 text-sm bg-black text-white border-4 border-black rounded-none hover:bg-gray-800 transition font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #000; text-shadow: 1px 1px 0px #000;">
        ← Back to Admin
    </a>
</div>

<div class="bg-surface border-4 border-black rounded-none overflow-hidden" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-300 bg-black border-b-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Level</th>
                    <th class="px-4 py-3">XP</th>
                    <th class="px-4 py-3">Streak</th>
                    <th class="px-4 py-3">Difficulty</th>
                    <th class="px-4 py-3">Last Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y-4 divide-black">
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-gray-800 transition font-bold" style="text-shadow: 1px 1px 0px #000;">
                    <td class="px-4 py-3 text-white">
                        <?= htmlspecialchars($u['username']) ?>
                        <?php if (($u['role'] ?? '') === 'admin'): ?>
                        <span class="ml-1 px-1.5 py-0.5 text-[10px] bg-yellow-500 text-black border-2 border-black rounded-none" style="box-shadow: inset 1px 1px 0px #fff, inset -1px -1px 0px #a16207; text-shadow: none;">ADMIN</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-300"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs border-2 border-black rounded-none bg-gray-700 <?= ($u['role'] ?? '') === 'admin' ? 'text-yellow-400' : 'text-white' ?>" style="box-shadow: inset 1px 1px 0px #9ca3af, inset -1px -1px 0px #374151;">
                            <?= ucfirst($u['role'] ?? 'learner') ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-white"><?= $u['level'] ?? 1 ?></td>
                    <td class="px-4 py-3 text-white"><?= number_format($u['xp'] ?? 0) ?></td>
                    <td class="px-4 py-3 text-white">🔥 <?= $u['streak'] ?? 0 ?></td>
                    <td class="px-4 py-3 text-gray-300"><?= ucfirst($u['difficulty'] ?? 'beginner') ?></td>
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= $u['last_active'] ?? '-' ?></td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= $baseUrl ?>/admin/users/<?= $u['id'] ?>" class="px-3 py-1 text-xs bg-white text-black border-2 border-black rounded-none hover:bg-gray-200 transition" style="box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc; text-shadow: none;">Edit</a>
                            <?php if ($u['id'] !== ($currentUser['id'] ?? '')): ?>
                            <form method="POST" action="<?= $baseUrl ?>/admin/users/<?= $u['id'] ?>/delete" onsubmit="return confirm('Delete this user?');" class="inline">
                                <button type="submit" class="px-3 py-1 text-xs bg-red-500 text-white border-2 border-black rounded-none hover:bg-red-600 transition" style="box-shadow: inset 2px 2px 0px #fca5a5, inset -2px -2px 0px #991b1b; text-shadow: 1px 1px 0px #000;">Delete</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
