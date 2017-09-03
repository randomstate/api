# randomstate/api
A flexible API package for PHP. Fits perfectly with Laravel and Laravel Doctrine.
This is a barebones package that is agnostic to any given PHP application or framework.
As a result, there is a lot of legwork to wire up the different components to get started.

**Please see randomstate/laravel-api for an out-of-the-box integration with Laravel that does this
all for you.**

# Installation

`composer require randomstate/api`

# Basic Usage

## Input Transformation
```php
<?php

use \RandomState\Api\Transformation\Manager;
use \RandomState\Api\Transformation\Fractal\Resolver;
use \RandomState\Api\Transformation\Fractal\Switchboard;
use \RandomState\Api\Transformation\Adapters\Fractal\CollectionAdapter;
use \RandomState\Api\Transformation\Adapters\Fractal\ItemAdapter;

$manager = new Manager;
$resolver = new Resolver()
$resolver->bind(MyOwnEntityFromSomewhere::class, MyOwnEntityFractalTransformer::class);
$switchboard = new Switchboard($resolver);

$manager->register(
    new CollectionAdapter($switchboard),
    new ItemAdapter($switchboard)
);

$data = new MyOwnEntityFromSomewhere;

$output = $manager->transform($data); // will use MyOwnEntityFractalTransformer via Fractal to transform the data
```