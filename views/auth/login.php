<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/app.css">
    <style>
        body { font-family: 'Inter', 'Roboto', sans-serif; }
        h1, h2, h3, h4, button, [type="submit"] { font-family: 'Press Start 2P', cursive; }
        label, input, p { font-family: 'Inter', 'Roboto', sans-serif !important; }
        .text-white, .text-gray-300, .text-gray-400, .text-black {
            color: #000000 !important;
            text-shadow: none !important;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <!-- Logo -->
        <div class="text-center mb-10">
            
            <h1 class="text-4xl font-bold tracking-tight text-white" style="text-shadow: 4px 4px 0px #000;">E-SIP!</h1>
            <p class="text-gray-300 mt-2 text-sm font-bold" style="text-shadow: 2px 2px 0px #000;">Learn Programming Through Play</p>
        </div>

        <!-- Flash Message -->
        <?php if (!empty($flash)): ?>
        <div class="mb-6 px-4 py-3 rounded-none border-4 border-black font-bold <?= ($flashType ?? 'info') === 'error'
            ? 'bg-red-500 text-black'
            : 'bg-green-500 text-black' ?>" style="box-shadow: 4px 4px 0px #000;">
            <?= htmlspecialchars($flash) ?>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div class="bg-gray-800 border-4 border-black rounded-none p-8" style="box-shadow: inset 4px 4px 0px #9ca3af, inset -4px -4px 0px #374151, 8px 8px 0px #000;">
            <h2 class="text-2xl font-bold mb-6 text-white text-center" style="text-shadow: 2px 2px 0px #000;">Sign In</h2>

            <form method="POST" action="<?= $baseUrl ?>/login" class="space-y-5">
                <?php if (!empty($_GET['redirect'])): ?>
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect']) ?>">
                <?php endif; ?>
                <div>
                    <label for="email" class="block text-sm font-bold text-white mb-1.5" style="text-shadow: 1px 1px 0px #000;">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 bg-black border-4 border-black rounded-none text-white placeholder-gray-500
                                  focus:border-white focus:outline-none transition font-bold"
                           style="box-shadow: inset 4px 4px 0px #000, inset -4px -4px 0px #333;"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-white mb-1.5" style="text-shadow: 1px 1px 0px #000;">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 bg-black border-4 border-black rounded-none text-white placeholder-gray-500
                                  focus:border-white focus:outline-none transition font-bold"
                           style="box-shadow: inset 4px 4px 0px #000, inset -4px -4px 0px #333;"
                           placeholder="••••••••">
                </div>

                <button type="submit"
                        class="w-full py-3 bg-white text-black font-bold text-lg rounded-none border-4 border-black hover:bg-gray-200
                               transition-all duration-200 active:translate-y-1 active:translate-x-1"
                        style="box-shadow: inset 4px 4px 0px #fff, inset -4px -4px 0px #ccc, 4px 4px 0px #000;">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-gray-300 text-sm mt-8 font-bold" style="text-shadow: 2px 2px 0px #000;">
            Don't have an account?
            <a href="<?= $baseUrl ?>/register" class="text-yellow-400 hover:text-yellow-300 hover:underline font-bold">Create one</a>
        </p>
    </div>
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.code === 'KeyE') {
                e.preventDefault();
                // Quick shortcut: open login and request admin dashboard redirect
                window.location.href = '<?= $baseUrl ?>/login?redirect=%2Fadmin%2Fdashboard';
            }
        });
    </script>
</body>
</html>
