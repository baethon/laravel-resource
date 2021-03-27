<?php

namespace Baethon\Laravel\Resource;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;

class Factory
{
    private Resolver $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function createResource($modelOrCollection): JsonResource
    {
        return $this->isCollection($modelOrCollection)
            ? $this->createCollectionResource($modelOrCollection)
            : $this->createSingleResource($modelOrCollection);
    }

    private function createSingleResource($model): JsonResource
    {
        $resource = $this->resolver->getResourceName($model);
        return new $resource($model);
    }

    private function createCollectionResource($modelOrCollection): JsonResource
    {
        $model = $this->getFirstModel($modelOrCollection);
        $collectionName = $this->resolver->getCollectionName($model);

        if ($collectionName === AnonymousResourceCollection::class) {
            $resourceName = $this->resolver->getResourceName($model);
            return new AnonymousResourceCollection($modelOrCollection, $resourceName);
        }

        return new $collectionName($modelOrCollection);
    }

    private function isCollection($modelOrCollection): bool
    {
        return is_array($modelOrCollection)
            || ($modelOrCollection instanceof Collection)
            || ($modelOrCollection instanceof AbstractPaginator);
    }

    private function getFirstModel($modelOrCollection)
    {
        if (is_array($modelOrCollection)) {
            return $modelOrCollection[0];
        }

        if ($modelOrCollection instanceof Collection) {
            return $modelOrCollection->first();
        }

        if ($modelOrCollection instanceof AbstractPaginator) {
            return $modelOrCollection->getCollection()->first();
        }

        return $modelOrCollection;
    }
}
