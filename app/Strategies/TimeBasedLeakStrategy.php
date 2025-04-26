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
        $intervals = floor($secondsPassed / $bucket['time_window']);

        if ($intervals > 0) {
            $bucket['requests'] = max(0, $bucket['requests'] - $intervals * $bucket['leak_rate']);
            $bucket['last_leak_reset'] = $lastLeakReset->addSeconds($intervals * $bucket['time_window'])->toDateTimeString();
        }

        return $bucket;
    }
}