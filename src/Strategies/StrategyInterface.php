<?php

namespace Baethon\Laravel\Resource\Strategies;

interface StrategyInterface
{
    public function resolveResourceName($model): ?string;

    public function resolveCollectionName($model): ?string;
}
