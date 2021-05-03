<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Tag;
use Baethon\Laravel\Resource\JsonResource;
use Baethon\Laravel\Resource\ServiceProvider;
use Illuminate\Contracts\Support\Arrayable;
use Orchestra\Testbench\TestCase;

class JsonResourceTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function test_it_wraps_relations_using_resource()
    {
        $post = new Post(['text' => 'Lorem ipsum']);
        $post->setRelation('dummy_tags', collect([
            new Tag(['name' => 'foo']),
            new Tag(['name' => 'bar']),
        ]));

        $resource = new JsonResource($post);
        $expected = [
            'text' => 'Lorem ipsum',
            'dummy_tags' => [
                ['name' => 'foo', 'tag' => true],
                ['name' => 'bar', 'tag' => true],
            ],
        ];

        $this->assertEquals($expected, $resource->resolve());
    }

    /**
     * @dataProvider otherResourcesProvider
     */
    public function test_it_handles_other_resources($resource, $expected)
    {
        $this->assertEquals($expected, (new JsonResource($resource))->resolve());
    }

    public function otherResourcesProvider()
    {
        return [
            [null, []],
            [['foo' => 'baz'], ['foo' => 'baz']],
            [
                new class () implements Arrayable
                {
                    public function toArray()
                    {
                        return ['foo' => 'baz'];
                    }
                },
                ['foo' => 'baz']
            ],
        ];
    }
}
