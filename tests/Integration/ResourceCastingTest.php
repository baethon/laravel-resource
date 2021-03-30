<?php

namespace Tests\Integration;

use App\Http\Resources\TagResourceCollection;
use App\Models\Post;
use App\Models\Tag;
use Baethon\Laravel\Resource\ServiceProvider;
use Orchestra\Testbench\TestCase;

use function Baethon\Laravel\Resource\resource;

class ResourceCastingTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function test_it_conditionally_loads_relations()
    {
        $fooTag = new Tag(['name' => 'foo']);
        $barTag = new Tag(['name' => 'bar']);

        $post = new Post(['text' => 'Lorem ipsum']);
        $post->setRelation('tags', collect([$fooTag, $barTag]));

        $data = resource($post)->resolve(request());

        $this->assertInstanceOf(TagResourceCollection::class, $data['tags']);
        $this->assertEquals(2, $data['tags']->resource->count());
    }

    public function test_it_conditionally_skips_relations()
    {
        $post = new Post(['text' => 'Lorem ipsum']);
        $data = resource($post)->resolve(request());

        $this->assertArrayNotHasKey('tags', $data);
    }
}
