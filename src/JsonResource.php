<?php

namespace Baethon\Laravel\Resource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource as BaseResource;

class JsonResource extends BaseResource
{
    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }

        if (is_array($this->resource)) {
            return $this->resource;
        }

        if ($this->resource instanceof Model) {
            return array_merge(
                $this->resource->attributesToArray(),
                $this->wrapRelations($request)
            );
        }

        return $this->resource->toArray();
    }

    private function wrapRelations($request): array
    {
        $getRelations = (function () {
            return $this->getArrayableRelations();
        })->bindTo($this->resource, $this->resource);

        $relations = [];

        foreach ($getRelations() as $key => $value) {
            if ($this->resource::$snakeAttributes) {
                $key = Str::snake($key);
            }

            $relations[$key] = resource($value)->toArray($request);
        }

        return $relations;
    }
}
