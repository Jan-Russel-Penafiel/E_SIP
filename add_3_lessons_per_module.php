<?php
/**
 * Add 3 more lessons to each of the 15 modules.
 * Run: php add_3_lessons_per_module.php
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

$additions = [
    'html-basics' => [
        makeLesson('html-l18', 'Keyboard & accesskey', 'Keyboard shortcuts', ['accesskey="x" triggers on Alt+x (or browser-specific).', 'tabindex for tab order.', 'Avoid conflicts with browser shortcuts.'], '<button accesskey="s">Save</button>', ['accesskey', 'tabindex', 'Shortcuts'], 'fill_blank', 'Attribute for keyboard shortcut?', '<button ___="s">Save</button>', ['accesskey', 'shortcut', 'key', 'hotkey'], 'accesskey'),
        makeLesson('html-l19', 'Lang attribute', 'Language of content', ['lang="en" on <html> or element for language.', 'Helps screen readers and translation.', 'hreflang on links for target language.'], '<html lang="en">', ['lang on html', 'hreflang', 'Accessibility'], 'predict_output', 'What does lang attribute help?', 'lang="es"', ['Screen readers and i18n', 'Styling', 'Validation', 'Nothing'], 'Screen readers and i18n'),
        makeLesson('html-l20', 'Role attribute', 'ARIA role', ['role="button" or role="navigation" describes purpose.', 'Use when native element is not used.', 'Improves accessibility for custom widgets.'], '<div role="button" tabindex="0">Click</div>', ['role', 'ARIA', 'Custom widgets'], 'fill_blank', 'Attribute to describe widget purpose?', '<div ___="button">', ['role', 'aria', 'type', 'widget'], 'role'),
    ],
    'html-basics-intermediate' => [
        makeLesson('html-int-l18', 'Popover API', 'Native popovers', ['popover attribute with id; showPopover()/hidePopover().', 'Light dismiss; no JS for close on outside click.', 'Popover on top layer.'], '<button popovertarget="p">Toggle</button><div id="p" popover>Content</div>', ['popover', 'showPopover', 'Light dismiss'], 'fill_blank', 'Attribute for native popover?', '<div id="x" ___>', ['popover', 'dialog', 'modal', 'overlay'], 'popover'),
        makeLesson('html-int-l19', 'Inert attribute', 'Exclude from accessibility', ['inert on subtree removes from a11y tree and blocks click.', 'Use for hidden modals or disabled sections.', 'Focus and click not possible inside.'], '<div inert>Hidden from a11y</div>', ['inert', 'A11y tree', 'Block interaction'], 'predict_output', 'What does inert do?', 'inert', ['Removes from a11y tree', 'Hides visually', 'Disables JS', 'Nothing'], 'Removes from a11y tree'),
        makeLesson('html-int-l20', 'Toggle event', 'Toggle button state', ['toggle event on <details> when open state changes.', 'event.newState is "open" or "closed".', 'Use for analytics or sync state.'], 'details.addEventListener("toggle", e => console.log(e.newState));', ['toggle event', 'newState', 'details'], 'fill_blank', 'Event when details open/close?', 'details.addEventListener("___", ...)', ['toggle', 'change', 'open', 'click'], 'toggle'),
    ],
    'html-basics-advanced' => [
        makeLesson('html-adv-l18', 'Priority Hints', 'fetchpriority', ['fetchpriority="high" for LCP, "low" for below-fold.', 'Works with img, link, script.', 'Hint only; browser may ignore.'], '<img src="hero.jpg" fetchpriority="high">', ['fetchpriority', 'high/low', 'LCP'], 'predict_output', 'Best use of fetchpriority="high"?', 'fetchpriority="high"', ['LCP image', 'Any image', 'Script', 'CSS'], 'LCP image'),
        makeLesson('html-adv-l19', 'Speculation Rules', 'Prefetch/prerender', ['<script type="speculationrules"> with rules JSON.', 'prefetch or prerender next pages.', 'Browser prefetches links user might click.'], '<script type="speculationrules">{"prefetch":[{"source":"list","where":{}}]}</script>', ['speculationrules', 'prefetch', 'prerender'], 'fill_blank', 'Script type for prefetch rules?', '<script type="___">', ['speculationrules', 'prefetch', 'prerender', 'speculation'], 'speculationrules'),
        makeLesson('html-adv-l20', 'Reporting API', 'Report errors', ['Reporting API sends reports to endpoint.', 'Content-Security-Policy report-uri or report-to.', 'Capture violations and crashes.'], 'Content-Security-Policy: default-src \'self\'; report-to csp', ['Reporting API', 'report-to', 'CSP'], 'predict_output', 'What does report-to do?', 'report-to csp', ['Sends reports to endpoint', 'Enables CSP', 'Logs only', 'Nothing'], 'Sends reports to endpoint'),
    ],
    'javascript-fundamentals' => [
        makeLesson('js-l18', 'Switch statement', 'Multiple branches', ['switch (x) { case 1: ... break; default: }.', 'break prevents fall-through.', 'Strict equality (===).'], 'switch(n){ case 1: break; default: }', ['switch case', 'break', 'default'], 'fill_blank', 'Keyword to avoid fall-through?', 'case 1: do(); ___;', ['break', 'stop', 'end', 'return'], 'break'),
        makeLesson('js-l19', 'Nested loops', 'Loops inside loops', ['for inside for for 2D iteration.', 'Inner loop runs fully per outer iteration.', 'Use for grids or matrix.'], 'for(let i=0;i<2;i++) for(let j=0;j<2;j++) console.log(i,j);', ['Nested for', 'Inner runs per outer', '2D'], 'predict_output', 'How many times inner loop? for(i=0;i<3;i++) for(j=0;j<2;j++)', '3*2', ['6', '3', '2', '5'], '6'),
        makeLesson('js-l20', 'Break and continue', 'Control loops', ['break exits the loop entirely.', 'continue skips to next iteration.', 'Works in for, while, do-while.'], 'for(let i=0;i<5;i++){ if(i===2) continue; console.log(i); }', ['break exit', 'continue skip', 'Loop control'], 'predict_output', 'What does continue do?', 'continue;', ['Skip to next iteration', 'Exit loop', 'Return', 'Stop'], 'Skip to next iteration'),
    ],
    'javascript-fundamentals-intermediate' => [
        makeLesson('js-int-l18', 'Event delegation', 'Delegate to parent', ['Attach listener on parent; use e.target to know which child.', 'One listener for many dynamic children.', 'Bubble phase required.'], 'parent.onclick = e => { if(e.target.matches(".btn")) handle(e.target); };', ['Delegate to parent', 'e.target', 'matches()'], 'predict_output', 'Where is listener in delegation?', 'Event delegation', ['On parent', 'On each child', 'On document', 'Nowhere'], 'On parent'),
        makeLesson('js-int-l19', 'RequestAnimationFrame', 'Smooth animation', ['requestAnimationFrame(cb) runs before next repaint.', 'Cancel with cancelAnimationFrame(id).', 'Better than setInterval for animation.'], 'function loop(){ draw(); requestAnimationFrame(loop); } requestAnimationFrame(loop);', ['requestAnimationFrame', 'Before repaint', 'Animation'], 'fill_blank', 'API for animation loop?', '___AnimationFrame(callback);', ['request', 'next', 'draw', 'animate'], 'request'),
        makeLesson('js-int-l20', 'MutationObserver', 'Watch DOM changes', ['new MutationObserver(cb).observe(node, { childList: true }).', 'Callback when children added/removed.', 'Use for dynamic content.'], 'new MutationObserver(records => {}).observe(el, { childList: true });', ['MutationObserver', 'observe', 'childList'], 'predict_output', 'What does MutationObserver watch?', 'MutationObserver', ['DOM changes', 'Clicks', 'Scroll', 'Network'], 'DOM changes'),
    ],
    'javascript-fundamentals-advanced' => [
        makeLesson('js-adv-l18', 'GlobalThis', 'Global object', ['globalThis is global in browser (window), Node (global), workers.', 'Use for cross-environment code.', 'ES2020.'], 'globalThis.setTimeout(() => {}, 1000);', ['globalThis', 'Cross-environment', 'ES2020'], 'fill_blank', 'Cross-env global object?', '___ .setTimeout(...)', ['globalThis', 'window', 'global', 'self'], 'globalThis'),
        makeLesson('js-adv-l19', 'Import meta', 'import.meta', ['import.meta.url is current module URL.', 'import.meta.resolve(specifier) for resolved URL.', 'Environment-specific.'], 'console.log(import.meta.url);', ['import.meta', 'url', 'resolve'], 'predict_output', 'What is import.meta.url?', 'import.meta.url', ['Current module URL', 'Base URL', 'Import path', 'undefined'], 'Current module URL'),
        makeLesson('js-adv-l20', 'Top-level await', 'Await in module', ['await at top level in ES modules (no async wrapper).', 'Module loads after promise settles.', 'Not in classic scripts.'], 'const data = await fetch("/api").then(r => r.json());', ['Top-level await', 'ES modules', 'No async'], 'fill_blank', 'Where is top-level await allowed?', 'In ___ modules', ['ES', 'CommonJS', 'Script', 'All'], 'ES'),
    ],
    'php-development' => [
        makeLesson('php-l18', 'Echo vs print', 'Output in PHP', ['echo can take multiple args; print returns 1.', 'echo is slightly faster; both output strings.', 'Short echo tag <?= for echo.'], '<?php echo "Hi"; print " there"; ?>', ['echo', 'print', '<?='], 'predict_output', 'What does <?= $x ?> do?', '<?= $x ?>', ['Echoes $x', 'Prints literally', 'Error', 'Nothing'], 'Echoes $x'),
        makeLesson('php-l19', 'Type casting', 'Force type', ['(int)$x, (string)$x, (array)$x, (bool)$x.', 'settype($var, "int") changes in place.', 'Casting creates new value.'], '<?php $n = (int)"42"; echo $n; ?>', ['(int) (string) etc', 'settype', 'New value'], 'fill_blank', 'Cast to integer?', '$n = ___ $str;', ['(int)', 'int(', 'toInt', 'integer'], '(int)'),
        makeLesson('php-l20', 'Null coalescing', '?? operator', ['$x ?? $y returns $x if set and not null, else $y.', '$a ?? $b ?? $c chains.', 'Shorthand for isset($x) ? $x : $y.'], '<?php $name = $_GET["name"] ?? "Guest"; ?>', ['??', 'Null coalescing', 'Default'], 'predict_output', 'What is null ?? 5?', 'null ?? 5', ['5', 'null', '0', 'Error'], '5'),
    ],
    'php-development-intermediate' => [
        makeLesson('php-int-l18', 'Late static binding', 'static::', ['static:: refers to called class; self:: to defining class.', 'Use in inheritance for correct class.', 'get_called_class() related.'], 'static::method()', ['static::', 'Late binding', 'Inheritance'], 'fill_blank', 'Keyword for called class?', '___::method()', ['static', 'self', 'parent', 'this'], 'static'),
        makeLesson('php-int-l19', 'Iterator interface', 'foreach over objects', ['Iterator: current, key, next, rewind, valid.', 'Implement to make class foreach-able.', 'IteratorAggregate with getIterator() simpler.'], 'class C implements Iterator { ... }', ['Iterator', 'foreach', 'current, next'], 'predict_output', 'What does Iterator allow?', 'implements Iterator', ['foreach over object', 'Serialization', 'Cloning', 'Nothing'], 'foreach over object'),
        makeLesson('php-int-l20', 'ArrayObject', 'Object as array', ['ArrayObject wraps array; array access, Countable, Iterator.', 'Use when you need object with array behavior.', 'Exchange array with getArrayCopy().'], 'new ArrayObject([1,2,3])', ['ArrayObject', 'Array access', 'Object'], 'fill_blank', 'Class that acts like array?', 'new ___ ([1,2,3])', ['ArrayObject', 'Array', 'Collection', 'List'], 'ArrayObject'),
    ],
    'php-development-advanced' => [
        makeLesson('php-adv-l18', 'Match arms', 'Multiple values', ['match($x) { 1, 2, 3 => "low"; default => "high"; }.', 'Comma-separated values in one arm.', 'Strict comparison.'], 'match($n) { 1,2 => "a", default => "b" };', ['Multiple in arm', 'Comma', 'Strict'], 'predict_output', 'match(2){ 1,2=>"y" }?', 'match(2){ 1,2=>"y" }', ['y', '2', 'null', 'Error'], 'y'),
        makeLesson('php-adv-l19', 'Constructor property promotion', 'Public in constructor', ['public function __construct(public string $name) { }.', 'Property created automatically.', 'Less boilerplate.'], 'function __construct(public string $n) {}', ['Promotion', 'public in params', 'PHP 8'], 'fill_blank', 'Where can promotion go?', '__construct(___ $x) {}', ['public', 'private', 'protected', 'var'], 'public'),
        makeLesson('php-adv-l20', 'Pure intersection types', 'A&B', ['Type1&Type2 for intersection (PHP 8.1+).', 'For interfaces: Countable&Iterator.', 'Both constraints must hold.'], 'function f(Countable&Iterator $c) {}', ['Intersection &', 'Multiple interfaces', 'PHP 8.1'], 'predict_output', 'What does A&B mean?', 'A&B', ['Both A and B', 'A or B', 'A then B', 'Neither'], 'Both A and B'),
    ],
    'sql-databases' => [
        makeLesson('sql-l18', 'Rename column', 'ALTER rename', ['ALTER TABLE t RENAME COLUMN a TO b; (syntax varies).', 'MySQL: CHANGE COLUMN a b TYPE.', 'Preserves data.'], 'ALTER TABLE users RENAME COLUMN name TO full_name;', ['RENAME COLUMN', 'CHANGE', 'Preserves data'], 'fill_blank', 'Statement to rename column?', 'ALTER TABLE t ___ COLUMN a TO b;', ['RENAME', 'CHANGE', 'ALTER', 'MODIFY'], 'RENAME'),
        makeLesson('sql-l19', 'TRUNCATE', 'Empty table fast', ['TRUNCATE TABLE t; removes all rows, resets identity.', 'Faster than DELETE; no WHERE.', 'DDL in some DBs; cannot rollback in MySQL.'], 'TRUNCATE TABLE temp_data;', ['TRUNCATE', 'Fast', 'No WHERE'], 'predict_output', 'Can TRUNCATE have WHERE?', 'TRUNCATE TABLE t', ['No', 'Yes', 'Optional', 'Depends'], 'No'),
        makeLesson('sql-l20', 'NOT NULL constraint', 'Require value', ['col TYPE NOT NULL in CREATE or ALTER.', 'Rejects NULL on insert/update.', 'Use for required fields.'], 'CREATE TABLE t (id INT NOT NULL);', ['NOT NULL', 'Reject NULL', 'Required'], 'fill_blank', 'Constraint to reject NULL?', 'col INT ___', ['NOT NULL', 'REQUIRED', 'NO NULL', 'CHECK'], 'NOT NULL'),
    ],
    'sql-databases-intermediate' => [
        makeLesson('sql-int-l18', 'EXPLAIN output', 'Read query plan', ['EXPLAIN shows table, type (ref, range, ALL), key, rows.', 'ALL = full scan; index = use index.', 'Optimize based on this.'], 'EXPLAIN SELECT * FROM users WHERE id = 1;', ['EXPLAIN', 'type, key, rows', 'Full scan'], 'predict_output', 'What does type=ALL mean?', 'EXPLAIN ... type=ALL', ['Full table scan', 'Index use', 'No rows', 'Error'], 'Full table scan'),
        makeLesson('sql-int-l19', 'Composite index', 'Multi-column index', ['INDEX (a, b) helps WHERE a=? AND b=? or WHERE a=?.', 'Order matters: (a,b) not same as (b,a).', 'Leftmost prefix.'], 'CREATE INDEX idx ON t(a, b);', ['Composite index', 'Column order', 'Leftmost prefix'], 'fill_blank', 'Index on multiple columns?', 'INDEX idx (a, ___);', ['b', 'b,c', 'b;', 'second'], 'b'),
        makeLesson('sql-int-l20', 'Covering index', 'Index only scan', ['All selected columns in index = no table access.', 'Faster; index has everything.', 'SELECT a,b from t with INDEX(a,b).'], 'CREATE INDEX idx ON t(a,b); SELECT a,b FROM t WHERE a=1;', ['Covering', 'Index only', 'No table read'], 'predict_output', 'When is index covering?', 'SELECT a FROM t WHERE a=1, index(a)', ['When all columns in index', 'Always', 'Never', 'With JOIN'], 'When all columns in index'),
    ],
    'sql-databases-advanced' => [
        makeLesson('sql-adv-l18', 'PERCENT_RANK', 'Window percentile', ['PERCENT_RANK() OVER (ORDER BY col) 0 to 1.', 'Proportion of rows strictly before current.', 'Similar to CUME_DIST.'], 'SELECT PERCENT_RANK() OVER (ORDER BY score) FROM t;', ['PERCENT_RANK', '0 to 1', 'Strictly before'], 'fill_blank', 'Window function for percentile rank?', '___() OVER (ORDER BY x)', ['PERCENT_RANK', 'RANK', 'PERCENT', 'RANK_PERCENT'], 'PERCENT_RANK'),
        makeLesson('sql-adv-l19', 'Frame clause', 'ROWS vs RANGE', ['OVER (ORDER BY x ROWS BETWEEN 2 PRECEDING AND CURRENT ROW).', 'ROWS = physical rows; RANGE = logical range.', 'Default frame often entire partition.'], 'SUM(x) OVER (ORDER BY d ROWS 2 PRECEDING)', ['ROWS BETWEEN', 'RANGE', 'Frame'], 'predict_output', 'ROWS vs RANGE?', 'ROWS BETWEEN', ['Physical rows', 'Logical values', 'Same', 'Invalid'], 'Physical rows'),
        makeLesson('sql-adv-l20', 'Index hints', 'Force index', ['USE INDEX (idx), FORCE INDEX, IGNORE INDEX (MySQL).', 'Override optimizer when wrong index chosen.', 'Last resort; fix schema/query first.'], 'SELECT * FROM t USE INDEX (idx_a) WHERE a=1;', ['USE INDEX', 'FORCE INDEX', 'Override optimizer'], 'fill_blank', 'Hint to use specific index?', 'FROM t ___ INDEX (idx_x)', ['USE', 'FORCE', 'WITH', 'INDEX'], 'USE'),
    ],
    'css-styling' => [
        makeLesson('css-l18', 'Inheritance', 'Inherited properties', ['color, font-*, line-height inherit.', 'border, margin, padding do not.', 'Use inherit to pass down.'], 'child { color: inherit; }', ['Inherited vs not', 'inherit keyword', 'font, color'], 'predict_output', 'Does margin inherit?', 'margin', ['No', 'Yes', 'Sometimes', 'Only block'], 'No'),
        makeLesson('css-l19', 'Initial value', 'Revert to default', ['initial sets property to spec default.', 'unset: inherit if inherited, else initial.', 'revert: browser default.'], 'color: initial;', ['initial', 'unset', 'revert'], 'fill_blank', 'Keyword for spec default?', 'color: ___;', ['initial', 'default', 'reset', 'normal'], 'initial'),
        makeLesson('css-l20', 'Universal selector', 'Select all', ['* matches every element.', 'Use for reset or low-specificity base.', 'Slower on huge DOM.'], '* { box-sizing: border-box; }', ['* universal', 'Reset', 'Specificity'], 'predict_output', 'What does * select?', '* { }', ['All elements', 'First', 'None', 'Body only'], 'All elements'),
    ],
    'css-styling-intermediate' => [
        makeLesson('css-int-l18', 'Z-index & stacking', 'Stacking context', ['z-index only works on positioned elements.', 'Stacking context: position + z-index or opacity < 1, etc.', 'Higher z-index on top within same context.'], 'position: relative; z-index: 10;', ['z-index', 'Positioned', 'Stacking context'], 'predict_output', 'When does z-index work?', 'z-index: 5', ['When element is positioned', 'Always', 'Never', 'On flex only'], 'When element is positioned'),
        makeLesson('css-int-l19', 'Clip-path', 'Clip to shape', ['clip-path: circle(), polygon(), url().', 'Clips element to shape.', 'Animatable.'], 'clip-path: circle(50%);', ['clip-path', 'circle, polygon', 'Clip shape'], 'fill_blank', 'Property to clip to shape?', '___-path: circle(50%);', ['clip', 'shape', 'mask', 'cut'], 'clip'),
        makeLesson('css-int-l20', 'Resize property', 'User resize', ['resize: both/horizontal/vertical; on overflow element.', 'User can drag to resize (e.g. textarea).', 'overflow not visible required.'], 'textarea { resize: vertical; }', ['resize', 'both, horizontal', 'overflow'], 'predict_output', 'Where is resize often used?', 'resize: both', ['textarea, div', 'img', 'button', 'Never'], 'textarea, div'),
    ],
    'css-styling-advanced' => [
        makeLesson('css-adv-l18', 'Color mix', 'mix() function', ['color-mix(in srgb, red 50%, blue) mixes colors.', 'Use for themes and tints.', 'CSS Color 5.'], 'color: color-mix(in srgb, red 50%, blue);', ['color-mix', 'in srgb', 'Themes'], 'fill_blank', 'Function to mix two colors?', 'color: color-___(in srgb, a 50%, b);', ['mix', 'blend', 'merge', 'add'], 'mix'),
        makeLesson('css-adv-l19', 'Anchor name', 'Position from anchor', ['anchor-name: --name on element; anchor() in another.', 'Position popover/tooltip relative to anchor.', 'New anchor spec.'], 'anchor-name: --btn; top: anchor(bottom);', ['anchor-name', 'anchor()', 'Position from'], 'predict_output', 'What does anchor-name do?', 'anchor-name: --x', ['Names element for anchor()', 'Sets name', 'Links', 'Nothing'], 'Names element for anchor()'),
        makeLesson('css-adv-l20', 'Nesting', 'CSS nesting', ['.parent { .child { } } nests rules.', '& for parent reference.', 'Native nesting (no preprocessor).'], '.card { .title { font-size: 1.2rem; } }', ['Nesting', '& parent', 'Native'], 'fill_blank', 'Symbol for parent in nesting?', '.a { &:hover { } }', ['&', '@', '%', 'parent'], '&'),
    ],
];

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
echo "Added 3 more lessons to each of the 15 modules. Total: 20 lessons, 1000 XP per module.\n";
