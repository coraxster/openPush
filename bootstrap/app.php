<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->configure('app');
$app->configure('fcm');

 $app->withFacades();
 $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(LaravelFCM\FCMServiceProvider::class);


$app->configureMonologUsing(function($monolog) {

    $app_name = config('app.app_name');
    $handlers['stream_handler'] = new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::INFO);

    if (env('LOG_SOCKET', false)){
        $exploded = explode(':', env('LOG_SOCKET'));
        $socket_handler = new \Monolog\Handler\GelfHandler(
            new Gelf\Publisher(
                new Gelf\Transport\UdpTransport(
                    str_replace('//', '',$exploded[1] ),
                    $exploded[2] ?? NULL
                )
            ),
            \Monolog\Logger::INFO);
        $socket_handler->setFormatter(new \Monolog\Formatter\GelfMessageFormatter($app_name, null, 'info.'));
        $handlers['socket_handler'] = $socket_handler;
    }

    if (env('FILE_LOGGING', false)){
        $fileHandler = new \Monolog\Handler\StreamHandler(
            storage_path('logs/lumen_' . date("Y.m.d") . '.log')
        );
        $handlers['file_handler'] = $fileHandler;
    }
    $wfgh = new \Monolog\Handler\WhatFailureGroupHandler($handlers);
    $monolog->pushHandler($wfgh);

    return $monolog;
});

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../routes/web.php';
});

return $app;
