<?php

namespace App\Repositories;

use App\Models\LeakyBucket;
use App\Services\StorageLoggerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class BucketRepository implements BucketRepositoryInterface
{
    private string $key = 'leaky_bucket:1';
    private array $config;
    private StorageLoggerService $logger;

    public function __construct(array $config, StorageLoggerService $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function get(): array
    {
        $redisBucket = $this->getRedisBucket();
        if ($redisBucket) {
            $this->logger->logRedisRead($this->key, $redisBucket);
            return array_merge([
                'capacity' => $this->config['capacity'],
                'leak_rate' => $this->config['leak_rate'],
                'time_window' => $this->config['time_window'],
            ], $redisBucket);
        }

        $this->logger->logDatabaseRead(LeakyBucket::class, 'firstOrCreate');
        $dbBucket = LeakyBucket::firstOrCreate(
            ['id' => 1],
            [
                'requests' => 0,
                'last_leak_reset' => Carbon::now(),
            ]
        );

        $bucket = [
            'requests' => $dbBucket->requests,
            'capacity' => $this->config['capacity'],
            'leak_rate' => $this->config['leak_rate'],
            'time_window' => $this->config['time_window'],
            'last_leak_reset' => $dbBucket->last_leak_reset->toDateTimeString(),
        ];

        $this->saveToRedis($bucket);

        return $bucket;
    }

    public function save(array $bucket): void
    {
        $this->logger->logDatabaseWrite(LeakyBucket::class, 'updateOrCreate', [
            'requests' => $bucket['requests'],
            'last_leak_reset' => $bucket['last_leak_reset'],
        ]);
        LeakyBucket::updateOrCreate(
            ['id' => 1],
            [
                'requests' => $bucket['requests'],
                'last_leak_reset' => Carbon::parse($bucket['last_leak_reset']),
                'capacity' => $this->config['capacity'],
            ]
        );

        $this->saveToRedis($bucket);
    }

    private function getRedisBucket(): array
    {
        try {
            $data = Redis::hgetall($this->key) ?: [];
            $this->logger->logRedisRead($this->key, $data);

            if (!empty($data)) {
                return [
                    'requests' => (int) ($data['requests'] ?? 0),
                    'last_leak_reset' => $data['last_leak_reset'] ?? Carbon::now()->toDateTimeString(),
                ];
            }

            return [];
        } catch (\Exception $e) {
            $this->logger->logRedisReadError($this->key, $e->getMessage());
            return [];
        }
    }


    private function saveToRedis(array $bucket): void
    {
        try {
            Redis::hmset($this->key, [
                'requests' => $bucket['requests'],
                'last_leak_reset' => $bucket['last_leak_reset'],
            ]);
            Redis::expire($this->key, 3600);
            $this->logger->logRedisWrite($this->key, [
                'requests' => $bucket['requests'],
                'last_leak_reset' => $bucket['last_leak_reset'],
            ]);
        } catch (\Exception $e) {
            $this->logger->logRedisWriteError($this->key, $e->getMessage());
        }
    }
}