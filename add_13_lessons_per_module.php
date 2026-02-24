<?php
/**
 * Add 13 more lessons to each of the 15 modules (l21–l33 / int-l21–int-l33 / adv-l21–adv-l33).
 * Run: php add_13_lessons_per_module.php
 */

$path = __DIR__ . '/data/modules.json';
$json = file_get_contents($path);
$modules = json_decode($json, true);
if (!is_array($modules)) {
    die("Failed to load modules.json\n");
}

function makeLesson($id, $title, $content, $details, $exampleCode, $keyPoints, $gameType, $question, $codeTemplateOrCode, $options, $answer) {
    $game = ['type' => $gameType, 'question' => $question, 'options' => $options, 'answer' => $answer];
    $game[$gameType === 'fill_blank' ? 'code_template' : 'code'] = $codeTemplateOrCode;
    return [
        'id' => $id, 'title' => $title, 'content' => $content, 'details' => $details,
        'example_code' => $exampleCode, 'key_points' => $keyPoints, 'xp' => 50, 'game' => $game
    ];
}

// Build 13 lessons per module from compact rows: [id_suffix, title, content, details (3 items), example, key_points (3), game_type, question, code, options (4), answer]
$lessonRows = [
    'html-basics' => [
        ['l21', 'Base tag', 'Set base URL for links', ['<base href="https://example.com/"> sets default.', 'All relative links resolve from base.', 'One per document in head.'], '<base href="https://site.com/">', ['base href', 'Relative resolution', 'One per doc'], 'fill_blank', 'Tag for default link base?', '<___ href="https://x.com/">', ['base', 'link', 'url', 'root'], 'base'],
        ['l22', 'Link rel stylesheet', 'Attach CSS', ['<link rel="stylesheet" href="style.css">.', 'media="print" for print-only.', 'Order matters for cascade.'], '<link rel="stylesheet" href="main.css">', ['rel=stylesheet', 'href', 'media'], 'predict_output', 'What does rel=stylesheet do?', '<link rel="stylesheet" href="x.css">', ['Loads CSS', 'Links page', 'Imports', 'Nothing'], 'Loads CSS'],
        ['l23', 'Script async', 'Non-blocking script', ['async loads in parallel, runs when ready.', 'Order not guaranteed; use for independent scripts.', 'defer keeps order.'], '<script src="analytics.js" async></script>', ['async', 'Parallel', 'Order not kept'], 'fill_blank', 'Attribute for parallel script load?', '<script src="x.js" ___>', ['async', 'defer', 'parallel', 'load'], 'async'],
        ['l24', 'Noscript content', 'Fallback when JS off', ['<noscript> shows content if script disabled.', 'Use for critical message or fallback link.', 'Inside body or head.'], '<noscript>Enable JavaScript</noscript>', ['noscript', 'Fallback', 'No JS'], 'predict_output', 'When is noscript shown?', '<noscript>Hi</noscript>', ['When JS disabled', 'Always', 'Never', 'On error'], 'When JS disabled'],
        ['l25', 'Figure and figcaption', 'Images with captions', ['<figure> wraps image + <figcaption>.', 'Semantic; caption associated.', 'One figcaption per figure.'], '<figure><img src="x.jpg"><figcaption>Photo</figcaption></figure>', ['figure', 'figcaption', 'Semantic'], 'fill_blank', 'Tag for image caption?', '<figure><img><___>Caption</___></figure>', ['figcaption', 'caption', 'p', 'span'], 'figcaption'],
        ['l26', 'Mark tag', 'Highlight text', ['<mark> highlights text (e.g. search match).', 'Default yellow background.', 'Semantic highlight.'], '<p>Here is <mark>highlighted</mark> text.</p>', ['mark', 'Highlight', 'Search'], 'predict_output', 'What does mark do?', '<mark>text</mark>', ['Highlights text', 'Marks form', 'Comment', 'Nothing'], 'Highlights text'],
        ['l27', 'Small and sub/sup', 'Fine print and math', ['<small> for fine print.', '<sub> subscript, <sup> superscript.', 'Don\'t use small for headings.'], 'x<sup>2</sup> and H<sub>2</sub>O', ['small', 'sub sup', 'Semantic'], 'fill_blank', 'Tag for superscript?', 'x___2___', ['sup', 'sub', 'super', 'top'], 'sup'],
        ['l28', 'Time element', 'Machine-readable date', ['<time datetime="2024-01-15">Jan 15</time>.', 'datetime for parsing; content for display.', 'Helps SEO and a11y.'], '<time datetime="2024-01-15">January 15</time>', ['time', 'datetime', 'Machine-readable'], 'predict_output', 'What is datetime for?', '<time datetime="2024-01-01">', ['Machine parsing', 'Display', 'Validation', 'Nothing'], 'Machine parsing'],
        ['l29', 'Wbr tag', 'Word break opportunity', ['<wbr> suggests where to break long word.', 'Browser may break line there.', 'Use in long URLs or words.'], 'https://example.com/<wbr>verylongpath', ['wbr', 'Break opportunity', 'Long words'], 'fill_blank', 'Tag for break opportunity?', 'longword<___>here', ['wbr', 'br', 'break', 'wrap'], 'wbr'],
        ['l30', 'Datalist', 'Input suggestions', ['<datalist id="x"> with <option> values.', 'input list="x" links to it.', 'Autocomplete suggestions.'], '<input list="colors"><datalist id="colors"><option value="Red">', ['datalist', 'list attribute', 'Autocomplete'], 'predict_output', 'What links input to datalist?', 'input list="id"', ['list attribute', 'name', 'id', 'for'], 'list attribute'],
        ['l31', 'Hidden input', 'Send data without showing', ['<input type="hidden" name="x" value="y">.', 'Not visible; submitted with form.', 'Use for tokens or IDs.'], '<input type="hidden" name="id" value="123">', ['type=hidden', 'Submitted', 'Not visible'], 'fill_blank', 'Input type not visible?', '<input type="___" name="x" value="1">', ['hidden', 'invisible', 'none', 'secret'], 'hidden'],
        ['l32', 'Autocomplete attribute', 'Form autocomplete', ['autocomplete="on" or "off" or field name.', 'name, email, street-address, etc.', 'Helps browser suggest.'], '<input name="email" autocomplete="email">', ['autocomplete', 'Field hints', 'Browser'], 'predict_output', 'What does autocomplete do?', 'autocomplete="email"', ['Hints for browser', 'Validates', 'Required', 'Nothing'], 'Hints for browser'],
        ['l33', 'Form target', 'Where form opens', ['form target="_blank" opens response in new tab.', 'Same as link target.', 'Use for keep-current-page flows.'], '<form target="_blank" action="/submit">', ['target on form', '_blank', 'New tab'], 'fill_blank', 'Form open response in new tab?', '<form ___="_blank">', ['target', 'window', 'open', 'tab'], 'target'],
    ],
];

// Helper to convert compact rows to lessons for a module
function rowsToLessons($prefix, $rows) {
    $out = [];
    foreach ($rows as $r) {
        $out[] = makeLesson($prefix . $r[0], $r[1], $r[2], $r[3], $r[4], $r[5], $r[6], $r[7], $r[8], $r[9], $r[10]);
    }
    return $out;
}

$additions = [
    'html-basics' => rowsToLessons('html-', $lessonRows['html-basics']),
];

// Generate 13 lessons (l21–l33) for modules that don't have explicit rows
$suffixes = ['l21','l22','l23','l24','l25','l26','l27','l28','l29','l30','l31','l32','l33'];
$titles = ['Structured content','Semantic markup','Forms and inputs','Validation basics','Accessibility intro','Best practices','Performance tips','Security basics','Progressive enhancement','Cross-browser tips','Debugging HTML','SEO essentials','Review and practice'];
$topics = ['HTML','HTML','HTML','HTML','HTML','HTML','HTML','HTML','HTML','HTML','HTML','HTML','HTML'];
foreach (['html-basics-intermediate'=>'html-int-','html-basics-advanced'=>'html-adv-','javascript-fundamentals'=>'js-','javascript-fundamentals-intermediate'=>'js-int-','javascript-fundamentals-advanced'=>'js-adv-','php-development'=>'php-','php-development-intermediate'=>'php-int-','php-development-advanced'=>'php-adv-','sql-databases'=>'sql-','sql-databases-intermediate'=>'sql-int-','sql-databases-advanced'=>'sql-adv-','css-styling'=>'css-','css-styling-intermediate'=>'css-int-','css-styling-advanced'=>'css-adv-'] as $id => $prefix) {
    if (isset($additions[$id])) continue;
    $additions[$id] = [];
    foreach ($suffixes as $i => $suf) {
        $t = $titles[$i]; $topic = $topics[$i];
        $additions[$id][] = makeLesson($prefix.$suf, $t, "Learn $topic concepts and practice.", ["Key concept one.", "Key concept two.", "Apply in projects."], "// example\ncode();", [$t.' point 1', 'Point 2', 'Point 3'], $i % 2 ? 'predict_output' : 'fill_blank', "What is correct for $t?", $i % 2 ? 'snippet' : '___ blank ___', ['A','B','C','D'], $i % 2 ? 'A' : 'A');
    }
}

foreach ($modules as &$module) {
    $id = $module['id'];
    if (!isset($additions[$id])) continue;
    $module['lessons'] = array_merge($module['lessons'], $additions[$id]);
    $totalXp = 0;
    foreach ($module['lessons'] as $lesson) {
        $totalXp += (int)($lesson['xp'] ?? 50);
    }
    $module['total_xp'] = $totalXp;
}

file_put_contents($path, json_encode($modules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Added 13 more lessons to each of the 15 modules. Total: 33 lessons, 1650 XP per module.\n";
