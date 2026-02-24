<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#333333',
                        secondary: '#ffffff',
                        accent: '#333333',
                        muted: '#9ca3af',
                        surface: '#111111',
                        'surface-light': '#1a1a1a',
                        border: '#2a2a2a',
                    }
                }
            }
        }
    </script>

    <!-- Custom styles -->
    <!-- Debug: echo baseUrl for diagnosing asset 404s -->
    <!-- BASE_URL=<?= $baseUrl ?> -->
    <script>console.log('E-SIP BASE_URL: "<?= $baseUrl ?>"');</script>
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/app.css">

    <!-- Prism.js Syntax Highlighting (Okaidia dark theme) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css">
    <style>
        /* Blend Prism into the app's dark theme */
        pre[class*="language-"], code[class*="language-"] {
            background: transparent !important;
            font-family: Consolas, 'Roboto Mono', monospace !important;
            font-size: 0.875rem !important;
            line-height: 1.625rem !important;
            tab-size: 4;
            margin: 0 !important;
            padding: 0 !important;
            text-shadow: none !important;
            white-space: pre !important;
        }
        .token.comment { color: #6b7280 !important; }
        /* Code editor overlay */
        .prism-editor-wrap {
            position: relative;
            background: #333333;
        }
        .prism-editor-wrap pre {
            position: absolute !important;
            top: 0; left: 0; right: 0; bottom: 0;
            overflow: hidden;
            pointer-events: none;
            padding: 1rem !important;
            white-space: pre !important;
        }
        .prism-editor-wrap textarea {
            position: relative;
            display: block;
            width: 100%;
            background: transparent;
            color: transparent;
            caret-color: #e5e7eb;
            outline: none;
            border: none;
            resize: none;
            font-family: Consolas, 'Roboto Mono', monospace;
            font-size: 0.875rem;
            line-height: 1.625rem;
            padding: 1rem;
            white-space: pre;
            overflow: auto;
            z-index: 1;
            -webkit-text-fill-color: transparent;
        }
        .prism-editor-wrap textarea::selection {
            background: rgba(255,255,255,0.2);
            -webkit-text-fill-color: transparent;
        }
    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* --- Base: readable font for all content --- */
        body {
            font-family: 'Inter', 'Roboto', sans-serif;
        }

        /* --- 8-bit font: UI chrome only --- */
        /* Sidebar, topbar, nav, headings, buttons keep the pixel aesthetic */
        h1, h2, h3, h4, h5, h6,
        aside,
        header,
        nav a, nav span,
        button, [type="submit"],
        .pixel-font {
            font-family: 'Press Start 2P', cursive;
        }

        /* --- Readable font: lesson and challenge content --- */
        /* Override headings back to Inter inside content areas */
        .lesson-content-text,
        .lesson-content-text h1,
        .lesson-content-text h2,
        .lesson-content-text h3,
        .lesson-content-text h4,
        .lesson-content-text h5,
        .lesson-content-text h6,
        .challenge-content,
        .challenge-content h1,
        .challenge-content h2,
        .challenge-content h3,
        .game-panel,
        .game-panel h1,
        .game-panel h2,
        .game-panel h3 {
            font-family: 'Inter', 'Roboto', sans-serif !important;
        }

        /* --- Code font: monospace for all code contexts --- */
        .font-mono, code, pre,
        .code-editor, .code-output {
            font-family: Consolas, 'Roboto Mono', monospace !important;
        }
        
        /* Keep code editor dark and its original colors */
        .prism-editor-wrap {
            background-color: #333333 !important;
        }
        .prism-editor-wrap textarea, .prism-editor-wrap pre, .prism-editor-wrap code {
            background-color: transparent !important;
            color: #f8f8f2 !important; /* Default Prism text color */
        }
        /* Allow Prism tokens to keep their colors */
        .prism-editor-wrap .token {
            color: unset;
        }
        
        /* Force all text to be black */
        .text-white, .text-gray-300, .text-gray-400, .text-black, .text-gray-500, .text-gray-600, .text-gray-700, .text-gray-800, .text-gray-900 {
            color: #000000 !important;
            text-shadow: none !important;
        }
    </style>
</head>
<body class="text-white min-h-screen flex">

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-surface border-r-4 border-gray-600 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col" style="box-shadow: inset -4px 0px 0px #555555;">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b-4 border-gray-600" style="box-shadow: inset 0px -4px 0px #555555;">
            <a href="<?= $baseUrl ?>/dashboard" class="flex items-center gap-3 w-full justify-center">
                <span class="text-xl font-bold tracking-tight text-white" style="text-shadow: 2px 2px 0px #000;">E-SIP!</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <?php
            $navItems = [
                ['path' => '/dashboard',   'label' => 'Dashboard',   'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'],
                ['path' => '/modules',     'label' => 'Modules',     'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'],
                ['path' => '/challenges',  'label' => 'Challenges',  'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>'],
                ['path' => '/leaderboard', 'label' => 'Leaderboard', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'],
                ['path' => '/profile',     'label' => 'Profile',     'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'],
            ];

            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $currentPath = str_replace($baseUrl, '', $currentPath);
            $currentPath = '/' . trim($currentPath, '/');

            // If we're inside the admin section, hide the main nav links and show only admin UI
            $adminActive = str_starts_with($currentPath, '/admin');
            if (!$adminActive) {
                foreach ($navItems as $item):
                    $isActive = str_starts_with($currentPath, $item['path']);
                    $activeClass = $isActive
                        ? 'bg-white text-black border-4 border-gray-600 rounded-none'
                        : 'text-gray-300 hover:text-white hover:bg-surface border-4 border-transparent hover:border-gray-600 rounded-none';
                    $activeStyle = $isActive
                        ? 'box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc; text-shadow: 1px 1px 0px #fff;'
                        : 'text-shadow: 1px 1px 0px #000;';
                ?>
                <a href="<?= $baseUrl . $item['path'] ?>"
                   class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 font-bold <?= $activeClass ?>" style="<?= $activeStyle ?>">
                    <?= $item['icon'] ?>
                    <span class="font-bold text-sm"><?= $item['label'] ?></span>
                </a>
                <?php endforeach; 
            }
            ?>

            <?php if ($isLoggedIn && $currentUser && ($currentUser['role'] ?? '') === 'admin'): ?>
            
                <?php
                $adminActive = str_starts_with($currentPath, '/admin');
                $adminClass = $adminActive ? 'bg-white text-black border-4 border-gray-600 rounded-none' : 'text-gray-300 hover:text-white hover:bg-surface border-4 border-transparent hover:border-gray-600 rounded-none';
                $adminStyle = $adminActive ? 'box-shadow: inset 2px 2px 0px #fff, inset -2px -2px 0px #ccc; text-shadow: 1px 1px 0px #fff;' : 'text-shadow: 1px 1px 0px #000;';
                ?>
                <a href="<?= $baseUrl ?>/admin"
                   class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 font-bold <?= $adminClass ?>" style="<?= $adminStyle ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-bold text-sm">Admin Panel</span>
                </a>
                <div class="mt-3 space-y-2 px-1">
                    <a href="<?= $baseUrl ?>/admin/users" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 text-gray-300 hover:text-white hover:bg-surface border-4 border-transparent hover:border-gray-600 rounded-none font-bold" style="text-shadow: 1px 1px 0px #333333;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM2 20c0-3.314 2.686-6 6-6h8c3.314 0 6 2.686 6 6"></path></svg>
                        <span class="text-sm font-bold">Manage Users</span>
                    </a>
                    <a href="<?= $baseUrl ?>/admin/modules" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 text-gray-300 hover:text-white hover:bg-surface border-4 border-transparent hover:border-gray-600 rounded-none font-bold" style="text-shadow: 1px 1px 0px #333333;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path></svg>
                        <span class="text-sm font-bold">Manage Modules</span>
                    </a>
                    <a href="<?= $baseUrl ?>/admin/challenges" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 text-gray-300 hover:text-white hover:bg-surface border-4 border-transparent hover:border-gray-600 rounded-none font-bold" style="text-shadow: 1px 1px 0px #333333;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path></svg>
                        <span class="text-sm font-bold">Manage Challenges</span>
                    </a>
                    
                </div>
            </div>
            <?php endif; ?>
        </nav>

        <!-- User Info -->
        <?php if ($isLoggedIn && $currentUser): ?>
        <div class="p-4 border-t-4 border-gray-600" style="box-shadow: inset 0px 4px 0px #555555;">
            <div class="mb-3 text-center">
                <div class="min-w-0">
                    <p class="text-sm font-bold truncate text-white" style="text-shadow: 1px 1px 0px #000;"><?= htmlspecialchars($currentUser['username']) ?></p>
                    <p class="text-xs text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">Level <?= $currentUser['level'] ?></p>
                </div>
            </div>
                <a href="<?= $baseUrl ?>/logout"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-white bg-surface border-4 border-gray-600 rounded-none hover:bg-gray-800 transition-all font-bold" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #333333; text-shadow: 1px 1px 0px #333333;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </a>
        </div>
        <?php endif; ?>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Top Bar -->
        <header class="h-16 border-b-4 border-gray-600 bg-surface flex items-center justify-between px-6 sticky top-0 z-40" style="box-shadow: inset 0px -4px 0px #555555;">
            <!-- Mobile menu toggle -->
            <button id="menuToggle" class="lg:hidden p-2 text-white bg-surface border-4 border-gray-600 rounded-none hover:bg-gray-800" style="box-shadow: inset 2px 2px 0px #555, inset -2px -2px 0px #333333;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="flex-1"></div>

            <?php if ($isLoggedIn && $currentUser): ?>
            <!-- Header stats: full on md+, compact dropdown on small screens -->
            <div class="relative">
                <!-- Full stats (desktop/tablet) -->
                <div id="statsFull" class="hidden md:flex items-center gap-4">
                    <div class="flex items-center gap-2 text-sm bg-surface border-4 border-gray-600 rounded-none px-3 py-1" style="box-shadow: inset 2px 2px 0px #333333;">
                        <span class="text-yellow-400" style="text-shadow: 1px 1px 0px #000;">⚡</span>
                        <span class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">XP:</span>
                        <span class="font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= $currentUser['xp'] ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm bg-surface border-4 border-gray-600 rounded-none px-3 py-1" style="box-shadow: inset 2px 2px 0px #333333;">
                        <span style="text-shadow: 1px 1px 0px #000;">🔥</span>
                        <span class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">Streak:</span>
                        <span class="font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= $currentUser['streak'] ?? 0 ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm bg-surface border-4 border-gray-600 rounded-none px-3 py-1" style="box-shadow: inset 2px 2px 0px #333333;">
                        <span style="text-shadow: 1px 1px 0px #000;">🎯</span>
                        <span class="text-gray-300 font-bold" style="text-shadow: 1px 1px 0px #000;">Level:</span>
                        <span class="font-bold text-white" style="text-shadow: 1px 1px 0px #000;"><?= $currentUser['level'] ?></span>
                    </div>
                </div>

                <!-- Compact mobile stats: single button that toggles a dropdown -->
                <div id="statsMobile" class="flex md:hidden items-center">
                    <button id="statsToggle" class="flex items-center gap-2 text-sm bg-surface border-4 border-gray-600 rounded-none px-3 py-1 font-bold" style="box-shadow: inset 2px 2px 0px #333333;">
                        <span class="text-yellow-400">⚡</span>
                        <span><?= $currentUser['xp'] ?></span>
                        <svg class="w-3 h-3 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>

                    <div id="statsDropdown" class="hidden absolute right-6 top-14 w-48 bg-surface border-4 border-gray-600 rounded-none p-2 z-50" style="box-shadow: 0 6px 18px rgba(0,0,0,0.5);">
                        <div class="flex items-center gap-2 text-sm px-2 py-2">
                            <span class="text-yellow-400">⚡</span>
                            <span class="text-gray-300 font-bold">XP:</span>
                            <span class="font-bold text-white"><?= $currentUser['xp'] ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm px-2 py-2 border-t border-gray-700">
                            <span>🔥</span>
                            <span class="text-gray-300 font-bold">Streak:</span>
                            <span class="font-bold text-white"><?= $currentUser['streak'] ?? 0 ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm px-2 py-2 border-t border-gray-700">
                            <span>🎯</span>
                            <span class="text-gray-300 font-bold">Level:</span>
                            <span class="font-bold text-white"><?= $currentUser['level'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </header>

        <!-- Flash Messages -->
        <?php if ($flash): ?>
        <div id="flashMessage" class="mx-6 mt-4">
            <div class="px-4 py-3 border-4 border-gray-600 rounded-none <?= $flashType === 'error'
                ? 'bg-red-900 text-red-100'
                : 'bg-green-900 text-green-100' ?> flex items-center justify-between font-bold" style="box-shadow: inset 4px 4px 0px <?= $flashType === 'error' ? '#f87171' : '#86efac' ?>, inset -4px -4px 0px <?= $flashType === 'error' ? '#7f1d1d' : '#14532d' ?>; text-shadow: 1px 1px 0px #000;">
                <span><?= htmlspecialchars($flash) ?></span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="p-6">
            <?= $content ?>
        </main>

        <!-- Footer -->
        <footer class="border-t-4 border-gray-600 px-6 py-4 text-center text-xs text-gray-400 font-bold" style="box-shadow: inset 0px 4px 0px #555555; text-shadow: 1px 1px 0px #333333;">
            &copy; <?= date('Y') ?> E-SIP &mdash; Educational System for Interactive Programming. All rights reserved.
        </footer>
    </div>

    <!-- Mobile overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden lg:hidden" style="background: rgba(51,51,51,0.5)" onclick="toggleSidebar()"></div>

    <!-- Prism.js core + autoloader (loads language grammars on demand) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script>
        if (typeof Prism !== 'undefined' && Prism.plugins && Prism.plugins.autoloader) {
            Prism.plugins.autoloader.languages_path =
                'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/';
        }
    </script>

    <!-- App JavaScript -->
    <script src="<?= $baseUrl ?>/assets/js/app.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        document.getElementById('menuToggle')?.addEventListener('click', toggleSidebar);

        // Auto-hide flash messages
        setTimeout(() => {
            const flash = document.getElementById('flashMessage');
            if (flash) flash.style.opacity = '0';
            setTimeout(() => flash?.remove(), 300);
        }, 4000);

        // Ctrl+Alt+E → Admin Panel shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.code === 'KeyE') {
                e.preventDefault();
                window.location.href = '<?= $baseUrl ?>/admin';
            }
        });
        // Header stats dropdown (mobile)
        (function(){
            const toggle = document.getElementById('statsToggle');
            const dropdown = document.getElementById('statsDropdown');
            if (!toggle || !dropdown) return;

            toggle.addEventListener('click', function(e){
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            // Close when clicking outside
            document.addEventListener('click', function(e){
                if (!dropdown.classList.contains('hidden')) {
                    // if click occurred outside dropdown and toggle
                    if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                }
            });

            // Close on escape
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') dropdown.classList.add('hidden');
            });
        })();
    </script>
</body>
</html>
