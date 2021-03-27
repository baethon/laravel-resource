<?php

namespace Baethon\Laravel\Resource\Strategies;

final class DefaultNamingStrategy implements StrategyInterface
{
    public function resolveResourceName($model): ?string
    {
        $baseName = class_basename($model);

        return class_exists($resourceName = $this->getResourceName($baseName, 'Resource'))
            ? $resourceName
            : null;
    }

    public function resolveCollectionName($model): ?string
    {
        $baseName = class_basename($model);

        if (class_exists($collectionName = $this->getResourceName($baseName, 'Collection'))) {
            return $collectionName;
        }

        if (class_exists($collectionName = $this->getResourceName($baseName, 'ResourceCollection'))) {
            return $collectionName;
        }

        return null;
    }

    private function getResourceName(string $baseName, string $postfix): string
    {
        return "App\\Http\\Resources\\{$baseName}{$postfix}";
    }
}
