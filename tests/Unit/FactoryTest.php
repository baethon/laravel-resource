<?php

namespace Tests\Unit;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Baethon\Laravel\Resource\Factory;
use Baethon\Laravel\Resource\Resolver;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\AbstractPaginator;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function test_it_creates_a_resource()
    {
        $model = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(PostResource::class);

        $factory = new Factory($resolver);

        $this->assertEquals(new PostResource($model), $factory->createResource($model));
    }

    public function test_it_creates_default_resource()
    {
        $model = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(JsonResource::class);

        $factory = new Factory($resolver);

        $this->assertEquals(new JsonResource($model), $factory->createResource($model));
    }

    public function test_it_creates_collection_resource()
    {
        $collection = collect([$model = new Post()]);

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with($model)
            ->willReturn(PostCollection::class);

        $factory = new Factory($resolver);

        $this->assertEquals(new PostCollection($collection), $factory->createResource($collection));
    }

    public function test_it_creates_anonymous_collection_resource()
    {
        $collection = collect([$model = new Post()]);

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with($model)
            ->willReturn(AnonymousResourceCollection::class);

        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(PostResource::class);

        $factory = new Factory($resolver);

        $this->assertEquals(
            new AnonymousResourceCollection($collection, PostResource::class),
            $factory->createResource($collection)
        );
    }

    public function test_it_supports_arrays()
    {
        $collection = [$model = new Post()];

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with($model)
            ->willReturn(PostCollection::class);

        $factory = new Factory($resolver);

        $this->assertEquals(new PostCollection($collection), $factory->createResource($collection));
    }

    public function test_it_supports_pagination()
    {
        $model = new Post();
        $paginator = new class ($model) extends AbstractPaginator {
            public function __construct($model)
            {
                $this->items = collect([$model]);
            }
        };

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with($model)
            ->willReturn(PostCollection::class);

        $factory = new Factory($resolver);

        // The original ResourceCollection will mutate paginator items
        $this->assertEquals(new PostCollection(clone $paginator), $factory->createResource($paginator));
    }

    public function test_it_supports_emtpy_result()
    {
        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with(new MissingValue)
            ->willReturn(JsonResource::class);

        $factory = new Factory($resolver);
        $this->assertEquals(new JsonResource(new MissingValue), $factory->createResource(null));
    }

    public function test_it_supports_emtpy_results_list()
    {
        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with(new MissingValue)
            ->willReturn(AnonymousResourceCollection::class);

        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with(new MissingValue)
            ->willReturn(JsonResource::class);

        $collection = [];

        $factory = new Factory($resolver);
        $this->assertEquals(new AnonymousResourceCollection($collection, JsonResource::class), $factory->createResource($collection));
    }
}
