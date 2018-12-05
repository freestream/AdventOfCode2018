<?php
$input = trim(file_get_contents('input'));
$range = array_fill_keys(range('A', 'Z'), 0);

$answerOne = strlen(getNewPolymer($input));

foreach ($range as $letter => &$count) {
    $one = preg_replace("/({$letter})/i", '', $input);
    $two = getNewPolymer($one);
    $count = strlen($two);
}

asort($range);

$answerTwo = reset($range);

function getNewPolymer($input)
{
    $len    = strlen($input);
    $regex  = [];

    foreach (range('A', 'Z') as $letter) {
        $combo1 = strtolower($letter) . strtoupper($letter);
        $combo2 = strtoupper($letter) . strtolower($letter);

        $regex[] = "({$combo1})";
        $regex[] = "({$combo2})";
    }

    $regex = '/' . implode('|', $regex) . '/';

    do {
        $len = strlen($input);
        $input = preg_replace($regex, '', $input);
    } while ($len != strlen($input));

    return $input;
}

echo "There is {$answerOne} units remain after fully reacting the polymer and the length of the shortest polymer {$answerTwo}\n";

