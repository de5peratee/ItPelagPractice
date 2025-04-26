<?php


namespace App\Repositories;

interface BucketRepositoryInterface
{
    public function get(): array;
    public function save(array $bucket): void;
}