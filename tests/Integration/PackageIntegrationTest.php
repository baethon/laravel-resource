<?php

namespace Tests\Integration;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Baethon\Laravel\Resource\ServiceProvider;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Orchestra\Testbench\TestCase;

use function Baethon\Laravel\Resource\resource;

class PackageIntegrationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('resource.resources', [
            User::class => PostResource::class,
        ]);

        $app['config']->set('resource.collections', [
            User::class => PostCollection::class,
        ]);
    }

    public function test_it_uses_default_strategy()
    {
        $model = new Post;
        $this->assertEquals(new PostResource($model), resource($model));
    }

    public function test_it_uses_mapping()
    {
        $model = new User;
        $this->assertEquals(new PostResource($model), resource($model));
        $this->assertEquals(new PostCollection(collect([$model])), resource(collect([$model])));
    }

    public function test_it_supports_empty_model()
    {
        $this->assertEquals(new JsonResource(null), resource(null));
    }

    public function test_it_supports_emtpy_collections()
    {
        $this->assertEquals(new AnonymousResourceCollection([], JsonResource::class), resource([]));
    }
}
