<?php

namespace Tests\Unit\Strategies;

use App\Http\Resources\PostResource;
use App\Http\Resources\TagResourceCollection;
use App\Models\Post;
use App\Models\User;
use Baethon\Laravel\Resource\Strategies\CustomMappingStrategy;
use PHPUnit\Framework\TestCase;

class CustomMappingStrategyTest extends TestCase
{
    public function test_it_resolves_resource_name()
    {
        $strategy = new CustomMappingStrategy(
            [User::class => PostResource::class],
            [],
        );

        $this->assertEquals(PostResource::class, $strategy->resolveResourceName(new User));
        $this->assertNull($strategy->resolveResourceName(new Post));
    }

    public function test_it_resolves_collection_name()
    {
        $strategy = new CustomMappingStrategy(
            [],
            [User::class => TagResourceCollection::class],
        );

        $this->assertEquals(TagResourceCollection::class, $strategy->resolveCollectionName(new User));
        $this->assertNull($strategy->resolveCollectionName(new Post));
    }
}
