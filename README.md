# Error handler module for Stratify

## Installation

- 1. Install the package:

```
composer require stratify/error-handler-module
```

- 2. Enable the module:

```php
$app = new Application([
    'stratify/error-handler-module',
]);
```

- 3. Use the HTTP middleware:

```php
$http = pipe([
    ErrorHandlerMiddleware::class,

    // ...
]);
$app->http($http)->run();
```

The HTTP middleware catches all exceptions happening in sub-middlewares and displays an error page. Because of that, you probably want that middleware to run before all others middlewares.

The default behavior is to show a very simple error page: `Server error` (status code 500).

In `dev` environment the error page will be much more detailed thanks to the Whoops library. For security reasons (not exposing any sensitive information), this page only displays if you configure the application to run in `dev` environment:

```php
$app = new Application($modules, 'dev');
```
