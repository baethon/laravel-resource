<?php

namespace Baethon\Laravel\Resource;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class Resolver
{
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Get resource class for given resource
     *
     * @param object $resource
     * @return string
     */
    public function getResourceName(object $resource): string
    {
        if (isset($this->map[$class = get_class($resource)])) {
            return $this->map[$class];
        }

        $basename = class_basename($resource);

        return class_exists($class = "App\\Http\\Resources\\{$basename}Resource")
            ? $class
            : JsonResource::class;
    }

    /**
     * Try to resolve name for the collection resource
     *
     * Works only for resource with FQCN ending with "Resource".
     */
    public function getCollectionName(string $resourceName): ?string
    {
        if (! Str::endsWith($resourceName, 'Resource')) {
            return null;
        }

        if (class_exists($class = Str::replaceLast('Resource', 'Collection', $resourceName))) {
            return $class;
        }

        return null;
    }
}
