<?php

namespace Tests\Unit;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\User;
use Baethon\Laravel\Resource\Resolver;
use Baethon\Laravel\Resource\Strategies\StrategyInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    public function test_it_resolves_resource_name()
    {
        $model = new User();

        $firstStrategy = $this->createMock(StrategyInterface::class);
        $firstStrategy->expects($this->once())
            ->method('resolveResourceName')
            ->with($model)
            ->willReturn(null);

        $secondStrategy = $this->createMock(StrategyInterface::class);
        $secondStrategy->expects($this->once())
            ->method('resolveResourceName')
            ->with($model)
            ->willReturn(PostResource::class);

        $resolver = new Resolver($firstStrategy, $secondStrategy);

        $this->assertEquals(PostResource::class, $resolver->getResourceName($model));
    }

    public function test_it_fallbacks_to_default_resource()
    {
        $model = new User();

        $firstStrategy = $this->createMock(StrategyInterface::class);
        $firstStrategy->expects($this->once())
            ->method('resolveResourceName')
            ->with($model)
            ->willReturn(null);

        $resolver = new Resolver($firstStrategy);

        $this->assertEquals(JsonResource::class, $resolver->getResourceName($model));
    }

    public function test_it_resolves_collection_name()
    {
        $model = new User();

        $firstStrategy = $this->createMock(StrategyInterface::class);
        $firstStrategy->expects($this->once())
            ->method('resolveCollectionName')
            ->with($model)
            ->willReturn(null);

        $secondStrategy = $this->createMock(StrategyInterface::class);
        $secondStrategy->expects($this->once())
            ->method('resolveCollectionName')
            ->with($model)
            ->willReturn(PostCollection::class);

        $resolver = new Resolver($firstStrategy, $secondStrategy);

        $this->assertEquals(PostCollection::class, $resolver->getCollectionName($model));
    }

    public function test_it_fallbacks_to_default_collection()
    {
        $model = new User();

        $firstStrategy = $this->createMock(StrategyInterface::class);
        $firstStrategy->expects($this->once())
            ->method('resolveCollectionName')
            ->with($model)
            ->willReturn(null);

        $resolver = new Resolver($firstStrategy);

        $this->assertEquals(AnonymousResourceCollection::class, $resolver->getCollectionName($model));
    }
}
