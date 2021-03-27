<?php

namespace Baethon\Laravel\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

function resource($modelOrCollection): JsonResource {
    return app(Factory::class)->createResource($modelOrCollection);
}
