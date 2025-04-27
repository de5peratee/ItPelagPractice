<?php

namespace App\Console\Commands;

use App\Services\LeakyBucketService;
use Illuminate\Console\Command;

class LeakBucketCommand extends Command
{
    protected $signature = 'bucket:leak';
    protected $description = 'Apply leak to the bucket';

    private LeakyBucketService $bucketService;

    public function handle()
    {
        $bucketService = app(LeakyBucketService::class);
        $bucketService->leak();
        \Log::info('Ведро уменьшилось в ' . now());
    }

}
