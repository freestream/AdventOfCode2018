<?php
$fp         = fopen('input', 'r');
$num        = 0;
$history    = [];
$before     = null;
$loop       = 0;
$first      = 0;

while (null === $before) {
    while (false !== ($line = fgets($fp))) {
        $num += (int) trim($line);

        if (null === $before && isset($history[$num])) {
            $before = $num;
        }

        $history[$num] = $num;
    }

    if (0 === $loop) {
        $first = $num;
    }

    $loop++;

    rewind($fp);
}

echo "The frequency result is {$first} and the first repeating number is $before\n";

