<?php

namespace Tests\Unit;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Baethon\Laravel\Resource\Factory;
use Baethon\Laravel\Resource\Resolver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function test_it_creates_resource_for_single_model()
    {
        $resource = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($resource)
            ->willReturn(PostResource::class);

        $factory = new Factory($resolver);

        $this->assertEquals(new PostResource($resource), $factory->createResource($resource));
    }

    public function test_it_creates_custom_collection()
    {
        $model = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(PostResource::class);

        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with(PostResource::class)
            ->willReturn(PostCollection::class);

        $factory = new Factory($resolver);
        $this->assertEquals(new PostCollection([$model]), $factory->createResource([$model]));
    }

    public function test_it_creates_generic_collection()
    {
        $model = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(PostResource::class);

        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with(PostResource::class)
            ->willReturn(null);

        $factory = new Factory($resolver);
        $this->assertEquals(PostResource::collection([$model]), $factory->createResource([$model]));
    }

    public function test_it_creates_generic_collection_for_generic_resource()
    {
        $model = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(JsonResource::class);

        $resolver->expects($this->once())
            ->method('getCollectionName')
            ->with(JsonResource::class)
            ->willReturn(null);

        $factory = new Factory($resolver);
        $this->assertEquals(JsonResource::collection([$model]), $factory->createResource([$model]));
    }

    public function test_it_supports_paginators()
    {
        $model = new Post();

        $resolver = $this->createMock(Resolver::class);
        $resolver->expects($this->once())
            ->method('getResourceName')
            ->with($model)
            ->willReturn(PostResource::class);

        $resolver->expects($this->any())
            ->method('getCollectionName')
            ->with(PostResource::class)
            ->willReturn(PostCollection::class);

        $collection = new Paginator([$model], 10);

        // The ResourceCollection instance will mutate
        // received collection.
        // It's important to pass the copy of it.
        $expected = new PostCollection(clone $collection);

        $factory = new Factory($resolver);
        $this->assertEquals($expected, $factory->createResource($collection));
    }
}
