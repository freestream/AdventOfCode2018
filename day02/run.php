<?php
$fp = fopen('input', 'r');

$twos = [];
$threes = [];
$lines = [];

while (false !== ($line = fgets($fp))) {
    $line   = trim($line);
    $counts = array_count_values(str_split($line));
    $flip   = array_flip($counts);

    if (isset($flip[2])) {
        $twos[] = $line;
    }

    if (isset($flip[3])) {
        $threes[] = $line;
    }

    $lines[] = $line;
}

$tested = [];
$common = '';

foreach ($lines as $a) {
    $as = str_split($a);

    foreach ($lines as $b) {
        $keyParts = [$a, $b];
        sort($keyParts);
        $key = md5(serialize($keyParts));

        if ($a === $b) {
            continue;
        }

        if (isset($tested[$key])) {
            continue;
        }

        $bs         = str_split($b);
        $correct    = [];
        $diffCount  = 0;

        foreach ($as as $i => $c) {
            if ($c == $bs[$i]) {
                $correct[] = $c;
            } else {
                $diffCount++;
            }
        }

        if (1 === $diffCount) {
            $common = implode($correct);
        }

        $tested[$key] = $key;
    }
}

$partOne = count($twos) * count($threes);
$partTwo = $common;

echo "The checksum for the list of box IDs is {$partOne} and the common letters between the two correct box IDs is $partTwo\n";

