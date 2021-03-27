<?php

namespace Baethon\Laravel\Resource;

use Baethon\Laravel\Resource\Strategies\StrategyInterface as Strategy;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Resolver
{
    /**
     * @var Strategy[]
     */
    private array $strategies;

    public function __construct(Strategy ... $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getResourceName($model): string
    {
        return $this->resolve(fn (Strategy $strategy) => $strategy->resolveResourceName($model))
            ?? JsonResource::class;
    }

    public function getCollectionName($model): string
    {
        return $this->resolve(fn (Strategy $strategy) => $strategy->resolveCollectionName($model))
            ?? AnonymousResourceCollection::class;
    }

    private function resolve(callable $fn): ?string
    {
        foreach ($this->strategies as $strategy) {
            if ($match = $fn($strategy)) {
                return $match;
            }
        }

        return null;
    }
}
