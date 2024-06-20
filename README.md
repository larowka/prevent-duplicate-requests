# Prevent Duplicate Requests Middleware for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/larowka/prevent-duplicate-requests.svg)](https://packagist.org/packages/larowka/prevent-duplicate-requests)
[![Total Downloads](https://img.shields.io/packagist/dt/larowka/prevent-duplicate-requests.svg)](https://packagist.org/packages/larowka/prevent-duplicate-requests)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHPUnit](https://github.com/larowka/prevent-duplicate-requests/actions/workflows/tests.yml/badge.svg)](https://github.com/larowka/prevent-duplicate-requests/actions/workflows/tests.yml)

Middleware for Laravel that prevents duplicate requests based on user actions.

This middleware is designed to manage and prevent duplicate requests within a specified timeframe, ensuring that only unique requests are processed.

- [Prevent Duplicate Requests Middleware for Laravel](#prevent-duplicate-requests-middleware-for-laravel)
    - [Installation](#installation)
    - [Usage](#usage)
        - [Laravel 11](#laravel-11)
          - [Global Middleware](#global-middleware)
          - [Assigning Middleware to Routes](#assigning-middleware-to-routes)
          - [Middleware Aliases](#middleware-aliases)
        - [Laravel 10](#laravel-10)
    - [Features](#features)
    - [Testing](#testing)
    - [Changelog](#changelog)
    - [Contributing](#contributing)
    - [Security](#security)
    - [Credits](#credits)
    - [License](#license)

## Installation

You can install the package via Composer:

```bash
composer require larowka/prevent-duplicate-requests
```

## Usage

### Laravel 11

#### Global Middleware

To apply middleware globally to every HTTP request in Laravel 11, you can use the withMiddleware method in your bootstrap/app.php file:

```php
use \Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests;

->withMiddleware(function ($middleware) {
    // ...
    $middleware->append(PreventDuplicateRequests::class);
});
```

#### Assigning Middleware to Routes

If you want to assign middleware to specific routes, use the middleware method when defining the route:

```php
Copy code
use \Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests;

Route::get('/example', function () {
    // Route logic...
})->middleware(PreventDuplicateRequests::class);
```

#### Middleware Aliases

You can define aliases for middleware in your bootstrap/app.php file to use shorter names for middleware classes:

```php
use \Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests;

->withMiddleware(function ($middleware) {
    $middleware->alias([
        'preventDuplicate' => PreventDuplicateRequests::class,
    ]);
});
```

Once defined, you can use the alias when assigning middleware to routes:

```php
Route::get('/example', function () {
    // Route logic...
})->middleware('preventDuplicate');
```
### Laravel 10

Add the middleware to your Laravel application's HTTP kernel:

```php
// app/Http/Kernel.php

protected $routeMiddleware = [
    // ...
    'preventDuplicate' => \Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests::class,
];
```

Apply the middleware to your routes:

```php
Route::middleware('preventDuplicate')->get('/example', function () {
    return 'Unique request handled.';
});
```

Or use globally:

```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests::class,
];
```

## Features

- Prevents Duplicate Requests: Blocks duplicate requests within a specified timeframe.
- Flexible Configuration: Customize the duration for which requests are cached.
- Supports Authenticated Users: Differentiates between authenticated and unauthenticated users.
- Idempotency Support: Ensures idempotent actions are enforced for user-specific operations.

## Testing

Run the tests using PHPUnit:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security issues, please email larowka@icloud.com instead of using the issue tracker.

## Credits

- [Serj Toropilin](https://github.com/larowka)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.