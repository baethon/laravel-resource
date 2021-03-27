<?php

namespace Tests\Unit\Strategies;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResourceCollection;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Baethon\Laravel\Resource\Strategies\DefaultNamingStrategy;
use PHPUnit\Framework\TestCase;

class DefaultNamingStrategyTest extends TestCase
{
    /** @dataProvider resourceNameProvider */
    public function test_it_resolves_resource_name($model, $expected)
    {
        $this->assertEquals($expected, (new DefaultNamingStrategy)->resolveResourceName($model));
    }

    public function resourceNameProvider()
    {
        return [
            [new Post(), PostResource::class],
            [new User(), null],
        ];
    }

    /** @dataProvider collectionNameProvider */
    public function test_it_resolves_collection_name($model, $expected)
    {
        $this->assertEquals($expected, (new DefaultNamingStrategy)->resolveCollectionName($model));
    }

    public function collectionNameProvider()
    {
        return [
            [new Post(), PostCollection::class],
            [new Tag(), TagResourceCollection::class],
            [new User(), null],
        ];
    }
}
