The missing XML support for Laravel's Request class.

This package is designed to work with the [Laravel](https://laravel.com) framework.

## Installation

Install via composer:

```
composer require Meta/request-xml
```

### Registering the service provider

For Laravel 5.4 and lower, add the following line to your ``config/app.php``:

```php
/*
 * Package Service Providers...
 */
Meta\RequestXml\Providers\RequestXmlServiceProvider::class,
```

For Laravel 5.5 and greater, the package will auto register the provider for you.

### Using Lumen

To register the service provider, add the following line to ``app/bootstrap/app.php``:

```php
$app->register(Meta\RequestXml\Providers\RequestXmlServiceProvider::class);
```

### Middleware

It's important to register the middleware so your application can convert an XML request and merge it into the Request object. You will then be able to run XML through Laravel's powerful validation system.

**Please note:**

Once you register the middleware, you do not need to do anything special to access your request xml. It will be available in the Request object like it would if it was a json or form request.

To setup the middleware, open up your ``app/Http/Kernel.php`` file.

To add the middleware globally:

```php
protected $middleware = [
    \Meta\RequestXml\Middleware\XmlRequest::class,
];
```

To add the middleware to web routes only:

```php
protected $middlewareGroups = [
    'web' => [
        \Meta\RequestXml\Middleware\XmlRequest::class,
    ],
];
```

To add the middleware to api routes only:

```php
protected $middlewareGroups = [
    'api' => [
        \Meta\RequestXml\Middleware\XmlRequest::class,
    ],
];
```

Or, if you want named middleware for specific routes:

```php
protected $routeMiddleware = [
    'xml' => \Meta\RequestXml\Middleware\XmlRequest::class,
];
```

## Quick start

### Determine if the request wants an xml response

```php
if (request()->wantsXml()) {
    // send xml response
}
```

### Determine if the request contains xml

```php
if (request()->isXml()) {
    // do something
}
```

### Get the converted xml as an array

```php
$data = request()->xml();
```

## Methods

**Request method**

``->wantsXml()``

Works very similar to Laravel's ``->wantsJson()`` method by returning a boolean. It will tell you if the incoming request would like to receive an XML response back.

**Request method**

``->isXml()``

Returns a boolean. This will tell you if the incoming request is XML.

**Request method**

``->xml()``

Returns an array. This converts the XML request into a PHP array. You are free to cast it to an object:

```php
$xml = (object) request()->xml();
```

Or wrap it in a collection:

```php
$xml = collect(request()->xml());
```

## Exceptions

In the event invalid XML is received in a request, the application will throw an Exception containing the raw, invalid XML. If you would like to handle this exception whenever it occurs in your application, you can easily catch it and supply your own code in your applications ``app/Exceptions/Handler.php`` like so:

```php
if ($exception instanceof \Meta\RequestXml\Exceptions\CouldNotParseXml) {
    // do something
}
```

## Testing

You can run the tests with:

```bash
./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.