<?php

return [
    'strategies' => [
        Baethon\Laravel\Resource\Strategies\CustomMappingStrategy::class,
        Baethon\Laravel\Resource\Strategies\DefaultNamingStrategy::class,
    ],

    'resources' => [
       // App\Models\Post::class => App\Http\Resources\PostResource::class,
    ],

    'collections' => [
       // App\Models\Post::class => App\Http\Resources\PostCollection::class,
    ],
];
