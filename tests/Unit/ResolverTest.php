<?php

namespace Tests\Unit;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Baethon\Laravel\Resource\Resolver;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    /**
     * @dataProvider resourceProvider
     */
    public function test_it_resolves_resource_name($resource, string $expected)
    {
        $result = (new Resolver([]))->getResourceName($resource);
        $this->assertEquals($expected, $result);
    }

    public function resourceProvider()
    {
        return [
            [new Post(), PostResource::class],
            [new User(), JsonResource::class],
        ];
    }

    public function test_it_supports_custom_map()
    {
        $resolver = new Resolver([
            User::class => PostResource::class,
        ]);

        $this->assertEquals(PostResource::class, $resolver->getResourceName(new User()));
    }

    /**
     * @dataProvider collectionProvider
     */
    public function test_it_resolves_collections($resource, ?string $expected)
    {
        $resolver = new Resolver([]);
        $this->assertEquals($expected, $resolver->getCollectionName($resource));
    }

    public function collectionProvider()
    {
        return [
            [PostResource::class, PostCollection::class],
            [JsonResource::class, null],
        ];
    }
}
