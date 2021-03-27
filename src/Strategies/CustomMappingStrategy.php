<?php

namespace Baethon\Laravel\Resource\Strategies;

final class CustomMappingStrategy implements StrategyInterface
{
    private array $resources;

    private array $collections;

    public function __construct(array $resources, array $collections)
    {
        $this->resources = $resources;
        $this->collections = $collections;
    }

    public function resolveResourceName($model): ?string
    {
        return $this->resources[get_class($model)] ?? null;
    }

    public function resolveCollectionName($model): ?string
    {
        return $this->collections[get_class($model)] ?? null;
    }
}
