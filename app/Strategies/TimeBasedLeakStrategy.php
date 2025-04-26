<?php

namespace App\Strategies;

use Carbon\Carbon;

class TimeBasedLeakStrategy implements LeakStrategyInterface
{
    public function apply(array $bucket): array
    {
        $now = Carbon::now();
        $lastLeakReset = Carbon::parse($bucket['last_leak_reset']);
        $secondsPassed = $lastLeakReset->diffInSeconds($now);

        if ($bucket['time_window'] <= 0) {
            $bucket['time_window'] = 1;
        }

        $intervals = intdiv($secondsPassed, $bucket['time_window']);

        if ($intervals > 0) {
            $leakAmount = $intervals * $bucket['leak_rate'];

            $bucket['requests'] = max(0, $bucket['requests'] - $leakAmount);
            $bucket['last_leak_reset'] = $lastLeakReset->addSeconds($intervals * $bucket['time_window'])->toDateTimeString();
        }

        return $bucket;
    }
}
