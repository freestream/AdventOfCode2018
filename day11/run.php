<?php
$input  = 7672;
$answerOne  = getLargest($input, 3)['pos'];
$answerTwo  = null;

for ($s = 1; $s <= 300; $s++) {
    $result = getLargest($input, $s, true);

    if ($answerTwo === null || $result['value'] > $answerTwo['value']) {
        $answerTwo = $result;
    }
}

$answerTwo = $answerTwo['pos'];

function getLargest($input, $size = 3, $custom = false) {
    $lpos = '';
    $lval = 0;

    for ($y = 1; $y <= 300 - $size; $y++) {
        for ($x = 1; $x <= 300 - $size; $x++) {
            $value  = 0;
            $ts     = max(1, $size - 1);

            for ($i = 0; $i <= $ts; $i++) {
                for ($h = 0; $h <= $ts; $h++) {
                    $tx = $x+$i;
                    $ty = $y+$h;

                    $rackId = $tx+10;
                    $pl     = $rackId * $ty;
                    $pl     += $input;
                    $pl     *= $rackId;
                    $digit  = floor((floor($pl % 1000 / 100) * 100) / 100);
                    $pl     = ($digit - 5);

                    $value  += $pl;
                }
            }

            if ($value > $lval) {
                $lval = $value;
                $lpos = (true === $custom) ? "{$x},{$y},{$size}" : "{$x},{$y}";
            }
        }
    }

    return [
        'pos'   => $lpos,
        'value' => $lval,
    ];
}

echo "The 3x3 square coordinate is {$answerOne} and the identifier for the largest square power is {$answerTwo} \n";

