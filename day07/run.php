<?php
$fp         = fopen('input', 'r');
$requires   = [];
$available  = [];

while (false !== ($line = fgets($fp))) {
    preg_match_all('/\s([A-Z])\s/', trim($line), $matches);

    list($letter, $before) = $matches[0];

    $letter = trim($letter);
    $before = trim($before);

    if (!isset($requires[$before])) {
        $requires[$before] = [];
    }

    $requires[$before][] = $letter;
    $available[$letter] = true;
}

$available = array_keys(array_diff_key($available, $requires));

$first          = '';
$copyAvailable  = $available;
$copyRequires   = $requires;

do {
    sort($copyAvailable);
    unset($copyRequires[$copyAvailable[0]]);

    $first .= array_shift($copyAvailable);

    foreach ($copyRequires as $step => $requirements) {
        foreach ($requirements as $st) {
            if (strpos($first, $st) === false) {
                continue 2;
            }
        }

        $copyAvailable[] = $step;
        unset($copyRequires[$step]);
    }
} while (!empty($copyAvailable));

$copyAvailable  = $available;
$copyRequires   = $requires;
$workers        = array_fill(0, 5, ['idle', null, 0]);
$second         = 0;
$completed      = [];
$notAvailable   = [];

while (count($completed) != strlen($first) && ++$second) {
    foreach ($copyRequires as $step => $requirements) {
        if (isset($completed[$step]) || isset($notAvailable[$step]) || in_array($step, $copyAvailable)) {
            continue;
        }

        foreach ($requirements as $st) {
            if (!isset($completed[$st])) {
                continue 2;
            }
        }

        $copyAvailable[] = $step;
    }

    foreach ($workers as $index => &$data) {
        if ($data[0] === 'idle' && !empty($copyAvailable)) {
            $data = ['working', $copyAvailable[0], $second];
            $notAvailable[array_shift($copyAvailable)] = true;
        }

        if ($data[0] === 'working' && $second - $data[2] === ord($data[1]) - 5) {
            $completed[$data[1]] = true;
            $data = ['idle', null, $second];
        }
    }
}

echo "The correct order to complete the instructions is {$first} and it will take {$two} seconds to complete\n";

