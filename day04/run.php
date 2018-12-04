<?php
$fp         = fopen('input', 'r');
$schedule   = [];

while (false !== ($line = fgets($fp))) {
    preg_match('/\[(\d+)-(\d+)-(\d+) (\d+:\d+)\][\W\w]+(#\d+|falls|wakes)/', trim($line), $matches);

    list(,$year, $month, $day, $time, $action) = $matches;

    $key = "{$year}-{$month}-{$day}";

    if (!isset($key)) {
        $schedule[$key] = [];
    }

    $schedule[$key][$time] = ltrim($action, '#');

    ksort($schedule[$key]);
}

ksort($schedule);

$highest    = [];
$sleeps     = [];
$guard      = '';
$sleepyTime = '';

foreach ($schedule as $date => $incidents) {
    foreach ($incidents as $time => $action) {
        $dateTime = "{$date} {$time}";

        if (is_numeric($action)) {
            $guard = (int) $action;
            if (!isset($sleeps[$guard])) {
                $sleeps[$guard] = [
                    'id'        => $guard,
                    'total'     => 0,
                    'minutes'   => [],
                ];
            }
        } elseif ('falls' === $action) {
            $sleepyTime = $dateTime;
        } else {
            $sleeps[$guard]['total'] += ((new DateTime($sleepyTime))->diff(new DateTime($dateTime)))->i;

            $period = new DatePeriod(
                 new DateTime($sleepyTime),
                 new DateInterval('PT1M'),
                 new DateTime($dateTime)
             );

            foreach ($period as $d) {
                $minute = (int) $d->format('i');

                if (!isset($sleeps[$guard]['minutes'][$minute])) {
                    $sleeps[$guard]['minutes'][$minute] = 1;
                } else {
                    $sleeps[$guard]['minutes'][$minute]++;
                }

                if (!isset($height[$guard]) || $sleeps[$guard]['minutes'][$minute] > $height[$guard]['total']) {
                    $height[$guard] = [
                        'id'        => $guard,
                        'total'     => $sleeps[$guard]['minutes'][$minute],
                        'minute'    => $minute,
                    ];
                }
            }

        }
    }
}

usort($sleeps, function ($a, $b) {
    return $a['total'] < $b['total'];
});

usort($height, function ($a, $b) {
    return $a['total'] < $b['total'];
});

$guard      = reset($sleeps);
$height     = reset($height);
$id         = $guard['id'];
$minutes    = $guard['minutes'];

arsort($minutes);

$minute     = key($minutes);
$first      = $id * $minute;
$id         = $height['id'];
$minute     = $height['minute'];
$second     = $id * $minute;

echo "The answer to strategy 1 is {$first} and for strategy 2 it is {$second}\n";

