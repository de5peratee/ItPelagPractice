<?php

namespace App\Strategies;

interface LeakStrategyInterface
{
    public function apply(array $bucket): array;
}