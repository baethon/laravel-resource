<?php

namespace Baethon\Laravel\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;

class Factory
{
    private Resolver $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param mixed $resource
     * @return ResourceCollection|JsonResource
     */
    public function createResource($resource): JsonResource
    {
        if ($this->isCollection($resource)) {
            return $this->createCollection($resource);
        }

        $resourceClassName = $this->resolver->getResourceName($resource);

        return new $resourceClassName($resource);
    }

    private function createCollection($resource): ResourceCollection
    {
        $resourceName = $this->resolver->getResourceName(
            $this->getFirstModel($resource)
        );
        $collectionName = $this->resolver->getCollectionName($resourceName);

        return $collectionName
            ? new $collectionName($resource)
            : $resourceName::collection($resource);
    }

    private function getFirstModel($resource)
    {
        if ($resource instanceof Collection) {
            return $resource->first();
        }

        if ($resource instanceof AbstractPaginator) {
            return $resource->getCollection()->first();
        }

        if (is_array($resource)) {
            return $resource[0];
        }

        throw new \InvalidArgumentException('Unsupported resource collection');
    }

    private function isCollection($resource): bool
    {
        if ($resource instanceof Collection) {
            return true;
        }

        if ($resource instanceof AbstractPaginator) {
            return true;
        }

        if (is_array($resource)) {
            return true;
        }

        return false;
    }
}
