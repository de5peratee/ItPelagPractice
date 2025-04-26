<?php

namespace App\Services;

use App\Repositories\BucketRepositoryInterface;
use App\Strategies\LeakStrategyInterface;
use Carbon\Carbon;

class LeakyBucketService
{
    private BucketRepositoryInterface $repository;
    private LeakStrategyInterface $leakStrategy;

    public function __construct(BucketRepositoryInterface $repository, LeakStrategyInterface $leakStrategy)
    {
        $this->repository = $repository;
        $this->leakStrategy = $leakStrategy;
    }

    public function allowRequest(): bool
    {
        $bucket = $this->repository->get();
        $bucket = $this->leakStrategy->apply($bucket);

        if ($bucket['requests'] >= $bucket['capacity']) {
            $this->repository->save($bucket);
            return false;
        }

        $bucket['requests']++;
        $this->repository->save($bucket);
        return true;
    }

    public function getState(): array
    {
        $bucket = $this->repository->get();
        $bucket = $this->leakStrategy->apply($bucket);
        $this->repository->save($bucket);
        return $bucket;
    }
}