<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class StorageLoggerService
{
    public function logRedisRead(string $key, array $data = null): void
    {
        if ($data) {
            Log::info('Redis read success', [
                'key' => $key,
                'data' => $data,
            ]);
        } else {
            Log::info('Redis read empty', [
                'key' => $key,
            ]);
        }
    }

    public function logRedisReadError(string $key, string $error): void
    {
        Log::error('Redis read error', [
            'key' => $key,
            'error' => $error,
        ]);
    }

    public function logRedisWrite(string $key, array $data): void
    {
        Log::info('Redis write', [
            'key' => $key,
            'data' => $data,
        ]);
    }

    public function logRedisWriteError(string $key, string $error): void
    {
        Log::error('Redis write error', [
            'key' => $key,
            'error' => $error,
        ]);
    }

    public function logDatabaseRead(string $model, string $action): void
    {
        Log::info('Database read', [
            'model' => $model,
            'action' => $action,
        ]);
    }

    public function logDatabaseWrite(string $model, string $action, array $data): void
    {
        Log::info('Database write', [
            'model' => $model,
            'action' => $action,
            'data' => $data,
        ]);
    }
}