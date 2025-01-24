<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Validation\DatabasePresenceVerifier;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Hashing\HashManager;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;

return function (ContainerBuilder $containerBuilder) {
    $container = new Container();
    $capsule   = new Capsule($container);

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => env('DB_HOST'),
        'database'  => env('DB_NAME'),
        'username'  => env('DB_USERNAME'),
        'password'  => env('DB_PASSWORD'),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    // ------------- db
    $container->instance('db', $capsule->getDatabaseManager());
    // ------------- end db

    // ---- validator --- // 
    $loader           = new FileLoader(new Filesystem(), __DIR__ . '/../resources/lang');
    $translator       = new Translator($loader, 'en');

    $container->singleton('validator', function ($app) use ($translator) {
        $validatorFactory = new ValidatorFactory($translator);

        $validatorFactory->setPresenceVerifier(
            new DatabasePresenceVerifier($app['db'])
        );

        return $validatorFactory;
    });

    // ---- end validator --- // 

    $containerBuilder->addDefinitions([
        'container' => $container,
    ]);

    Facade::setFacadeApplication($container);

    $containerBuilder->addDefinitions([
        /**
         * Logger interface
         */
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
