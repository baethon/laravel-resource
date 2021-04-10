# baethon/laravel-resource

![Example](https://raw.githubusercontent.com/baethon/laravel-resource/master/example.png)

The package provides a convenient factory function for [Laravel API Resources](https://laravel.com/docs/8.x/eloquent-resources). Based on the given model, it will try to find the corresponding API resource and return it. If the resource doesn't exist, it will use the base `JsonResource` class. It works with collections.

## Installation

```
composer require baethon/laravel-resource
```

## Example usage

```php
<?php

namespace App\Http\Controllers;

use function Baethon\Laravel\Resource\resource;

class UserController
{
    public function show(\App\Models\User $user)
    {
        return resource($user);
    }
}
```

Factory will work also with conditional loads:

```php
<?php

namespace App\Http\Resources;

use function Baethon\Laravel\Resource\resource;

class UserResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            'tags' => resource($this->whenLoaded('tags')),
        ];
    }
}
```

## How it works?

The package tries to follow the Laravels naming conventions. When creating a resource for `User` model, it will look for `UserResource`. When passing a collection of `User` instances, it will look for `UserResourceCollection` or `UserCollection`.

In the case of collections, the package won't wrap individual models in their respective resources. It will pass that responsibility to the collection resource instead.

## Rationale

When you decide to use API resources, you should create an individual resource for each model returned by the API. In many cases, they're not extended in any way, and it seems pointless to create a bunch of empty classes.

To avoid this, you might use the `JsonResource` for the "generic" models and create resources only for those models that include any logic. However, when the time comes to make a customized resource for one of the models, you'll have to update all the places where you previously used the `JsonResource`.

This is the moment when `resource()` shines. You simply need to create the customized resource, and the factory will automatically start using it instead of the base resource.

## Customization

There are two ways to customize the factory:
1. custom map
1. custom naming strategy

To change any of them, you'll have to publish the config files:

```
php artisan vendor:publish --provider="Baethon\Laravel\Resource\ServiceProvider"
```
## License 

The package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
