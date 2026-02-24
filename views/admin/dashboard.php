<?php
// Admin Dashboard View
use Core\Auth;

// Guard: ensure only admins can view this page — otherwise send to user dashboard
$auth = Auth::getInstance();
if (!$auth->isAdmin()) {
    $config = require __DIR__ . '/../../config/app.php';
    header('Location: ' . ($config['base_url'] ?? '') . '/dashboard');
    exit;
}
?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;">Admin Dashboard</h1>
    <p class="text-gray-300 text-sm mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">System overview and management controls</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-surface border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= $stats['total_users'] ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Users</p>
    </div>
    <div class="bg-surface border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= $stats['total_modules'] ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Modules</p>
    </div>
    <div class="bg-surface border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= $stats['total_challenges'] ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Challenges</p>
    </div>
    <div class="bg-surface border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= $stats['total_attempts'] ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Attempts</p>
    </div>
    <div class="bg-surface border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= $stats['success_rate'] ?>%</p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Success Rate</p>
    </div>
    <div class="bg-surface border-4 border-black rounded-none p-4 text-center" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <p class="text-2xl font-bold text-white" style="text-shadow: 2px 2px 0px #000;"><?= $stats['active_today'] ?></p>
        <p class="text-xs text-gray-300 mt-1 font-bold" style="text-shadow: 1px 1px 0px #000;">Active Today</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <h2 class="text-lg font-bold mb-4 text-white" style="text-shadow: 2px 2px 0px #000;">Recent Activity</h2>
        <?php if (empty($recentActivity)): ?>
        <p class="text-gray-300 text-sm font-bold" style="text-shadow: 1px 1px 0px #000;">No recent activity.</p>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b-4 border-black font-bold" style="text-shadow: 1px 1px 0px #000;">
                        <th class="pb-2">User</th>
                        <th class="pb-2">Challenge</th>
                        <th class="pb-2">Result</th>
                        <th class="pb-2">XP</th>
                        <th class="pb-2">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y-4 divide-black">
                    <?php foreach ($recentActivity as $act): ?>
                    <tr class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">
                        <td class="py-2 text-white"><?= htmlspecialchars($act['username']) ?></td>
                        <td class="py-2 font-mono text-xs"><?= htmlspecialchars($act['challenge_id'] ?? '-') ?></td>
                        <td class="py-2">
                            <?php if ($act['success'] ?? false): ?>
                            <span class="text-green-400">✓ Pass</span>
                            <?php else: ?>
                            <span class="text-red-400">✗ Fail</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2"><?= $act['xp_earned'] ?? 0 ?></td>
                        <td class="py-2 text-xs"><?= $act['time_taken'] ?? '-' ?>s</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- User Distribution -->
<div class="mt-6 grid lg:grid-cols-2 gap-6">
    <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <h2 class="text-lg font-bold mb-4 text-white" style="text-shadow: 2px 2px 0px #000;">Users by Role</h2>
        <?php
        $roles = array_count_values(array_column($users, 'role'));
        ?>
        <div class="space-y-3">
            <?php foreach ($roles as $role => $count): ?>
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= ucfirst($role) ?></span>
                <div class="flex items-center gap-2">
                    <div class="h-4 bg-surface border-2 border-black rounded-none w-32 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000;">
                        <div class="h-full bg-white" style="width: <?= ($count / max(count($users), 1)) * 100 ?>%; box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc;"></div>
                    </div>
                    <span class="text-xs text-gray-300 font-bold w-8 text-right" style="text-shadow: 1px 1px 0px #000;"><?= $count ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bg-surface border-4 border-black rounded-none p-6" style="box-shadow: inset 4px 4px 0px #c6c6c6, inset -4px -4px 0px #555555;">
        <h2 class="text-lg font-bold mb-4 text-white" style="text-shadow: 2px 2px 0px #000;">Modules by Difficulty</h2>
        <?php
        $diffs = array_count_values(array_column($modules, 'difficulty'));
        $diffColors = ['beginner' => 'bg-green-500', 'intermediate' => 'bg-yellow-500', 'advanced' => 'bg-red-500'];
        ?>
        <div class="space-y-3">
            <?php foreach ($diffs as $diff => $count): ?>
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= ucfirst($diff) ?></span>
                <div class="flex items-center gap-2">
                    <div class="h-4 bg-surface border-2 border-black rounded-none w-32 overflow-hidden" style="box-shadow: inset 2px 2px 0px #000;">
                        <div class="h-full <?= $diffColors[$diff] ?? 'bg-white' ?>" style="width: <?= ($count / max(count($modules), 1)) * 100 ?>%; box-shadow: inset 2px 2px 0px rgba(255,255,255,0.5), inset -2px -2px 0px rgba(0,0,0,0.2);"></div>
                    </div>
                    <span class="text-xs text-gray-300 font-bold w-8 text-right" style="text-shadow: 1px 1px 0px #000;"><?= $count ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
