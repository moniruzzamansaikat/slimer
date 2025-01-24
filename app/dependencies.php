<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        
        
        
        /**
         * Setup database connection using PDO
         */
        PDO::class => function () {
            $dbHost     = $_ENV['DB_HOST'];
            $dbName     = $_ENV['DB_NAME'];
            $dbUser     = $_ENV['DB_USERNAME'];
            $dbPassword = $_ENV['DB_PASSWORD'];

            return new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPassword);
        },

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
