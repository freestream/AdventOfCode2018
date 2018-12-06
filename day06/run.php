<?php

$fp     = fopen('input', 'r');
$count  = 0;

for ($i = 0; $i < 400; $i++) {
    for ($j = 0; $j < 400; $j++) {
        $min    = PHP_INT_MAX;
        $total  = 0;
        $p      = 0;
        $k      = 0;

        while (false !== ($line = fgets($fp))) {
            $point = explode(', ', trim($line));
            $dist  = abs($point[0] - $i) + abs($point[1] - $j);
            $total += $dist;

            if ($dist < $min) {
                $min = $dist;
                $p = $k;
            } elseif ($dist == $min) {
                $p = '.';
            }

            $k++;
        }

         rewind($fp);

        if ($total < 10000) {
            $count++;
        }

        $area[$p] = ($area[$p] ?? 0) + 1;

        if ($i == 0 || $i == 399 || $j == 0 || $j == 399) {
            $infinite[$p] = true;
        }
    }
}

$area = array_diff_key($area, $infinite);
rsort($area);

$first  = $area[0];
$two    = $count;

echo "The size of the largest area is {$first} and the size of the region containing all locations is $two\n";

