<?php
/**
 * E-SIP Application Configuration
 * Central configuration file for the Educational System for Interactive Programming
 */

return [
    'name'    => 'E-SIP',
    'version' => '1.0.0',
    'tagline' => 'Learn Programming Through Play',
    'base_url' => '/e_sip',

    // Data directory for JSON storage
    'data_dir' => __DIR__ . '/../data',

    // Session configuration
    'session' => [
        'lifetime' => 3600,       // 1 hour
        'name'     => 'esip_session',
    ],

    // XP and leveling configuration
    'gamification' => [
        'xp_per_challenge'    => 50,
        'xp_per_module'       => 200,
        'xp_bonus_streak'     => 25,
        'xp_level_multiplier' => 100, // XP needed = level * multiplier
        'max_level'           => 100,
    ],

    // Difficulty levels
    'difficulty' => [
        'beginner'     => ['label' => 'Beginner',     'xp_multiplier' => 1.0],
        'intermediate' => ['label' => 'Intermediate', 'xp_multiplier' => 1.5],
        'advanced'     => ['label' => 'Advanced',     'xp_multiplier' => 2.0],
    ],

    // Supported languages and frameworks
    // Icons use Simple Icons CDN: https://cdn.simpleicons.org/{slug}/{hex-color}
    'languages' => [
        'html'       => ['name' => 'HTML',       'icon' => '<img src="https://cdn.simpleicons.org/html5/E34F26"      class="w-5 h-5 inline-block align-middle" alt="HTML">',       'color' => '#E34F26'],
        'css'        => ['name' => 'CSS',        'icon' => '<img src="/here/assets/img/css.svg" class="w-5 h-5 inline-block align-middle" alt="CSS">',        'color' => '#1572B6'],
        'javascript' => ['name' => 'JavaScript', 'icon' => '<img src="https://cdn.simpleicons.org/javascript/F7DF1E" class="w-5 h-5 inline-block align-middle" alt="JavaScript">', 'color' => '#F7DF1E'],
        'php'        => ['name' => 'PHP',        'icon' => '<img src="https://cdn.simpleicons.org/php/777BB4"        class="w-5 h-5 inline-block align-middle" alt="PHP">',        'color' => '#777BB4'],
        'sql'        => ['name' => 'MySQL',      'icon' => '<img src="https://cdn.simpleicons.org/mysql/CC6600"      class="w-5 h-5 inline-block align-middle" alt="MySQL">',      'color' => '#CC6600'],
    ],

    // Local code execution — PHP via PHP_BINARY, JS via Node.js, SQL via SQLite in-memory
];
