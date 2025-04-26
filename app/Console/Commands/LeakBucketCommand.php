<?php

namespace App\Console\Commands;

use App\Services\LeakyBucketService;
use Illuminate\Console\Command;

class LeakBucketCommand extends Command
{
    protected $signature = 'bucket:leak';
    protected $description = 'Apply leak to the bucket';

    private LeakyBucketService $bucketService;

    public function __construct(LeakyBucketService $bucketService)
    {
        parent::__construct();
        $this->bucketService = $bucketService;
    }

    public function handle()
    {
        $this->bucketService->leak();
        \Log::info('Bucket leaked at ' . now());
    }
}
