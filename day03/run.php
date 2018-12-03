<?php
$fp         = fopen('input', 'r');
$grid       = [];
$ids        = [];
$overlaps   = 0;

while (false !== ($line = fgets($fp))) {
    preg_match_all('/(\d+)/', trim($line), $matches);
    $matches = $matches[0];
    $matches = array_map('intval', $matches);

    list($id, $left, $top, $width, $height) = $matches;

    $ids[$id] = $id;

    for ($x = 1; $x <= $width; $x++) {
        for ($y = 1; $y <= $height; $y++) {
            $pos = ($left + $x) . 'x' . ($top + $y);

            if (!isset($grid[$pos])) {
                $grid[$pos] = [$id];
            } else {
                if (count($grid[$pos]) === 1) {
                    $overlaps++;
                }
                $grid[$pos][] = $id;
            }
        }
    }
}

foreach ($grid as $item) {
    if (count($item) > 1) {
        foreach ($item as $id) {
            unset($ids[$id]);
        }
    }
}

$id = key($ids);

echo "There is {$overlaps} square inches of fabric that overlaps and the only claim that doesn't overlap is {$id}\n";

