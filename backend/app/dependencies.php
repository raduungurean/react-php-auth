<?php

declare(strict_types=1);

use App\Application\Listeners\AccountActivatedEmailListener;
use App\Application\Listeners\ForgotPasswordEmailListener;
use App\Application\Listeners\NewPasswordEmailListener;
use App\Application\Middleware\JwtAuthMiddleware;
use App\Application\Services\JwtService;
use App\Application\Services\JwtServiceInterface;
use App\Application\Settings\SettingsInterface;
use App\Domain\Event\AccountRegistered;
use App\Domain\Event\NewPasswordCreated;
use App\Domain\Event\NewPasswordRequested;
use App\Domain\User\UserRepository;
use App\Infrastructure\Mailer\Mailer;
use DI\ContainerBuilder;
use League\Event\EventDispatcher;
use Mailgun\Mailgun;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
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
        PDO::class => function (ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');
            $dsn = "mysql:host={$dbSettings['host']};dbname={$dbSettings['database']}";
            $username = $dbSettings['username'];
            $password = $dbSettings['password'];

            try {
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode = 'STRICT_TRANS_TABLES'"
                ]);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                // Handle connection errors
                throw new RuntimeException("Failed to connect to database: " . $e->getMessage());
            }
        },
        'jwtAuthMiddleware' => function (ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);
            $jwtSettings = $settings->get('jwt');
            return new JwtAuthMiddleware(
                $jwtSettings['secret'],
                $jwtSettings['algorithm']
            );
        },
        JwtServiceInterface::class => function ($c) {
            $settings = $c->get(SettingsInterface::class);
            $jwtSettings = $settings->get('jwt');
            return new JwtService($jwtSettings['secret'], $jwtSettings['algorithm']);
        },
        'mailer' => function (ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);
            $mailerSettings = $settings->get('mail');
            return Mailgun::create($mailerSettings['secret'], 'https://' . $mailerSettings['host']);
        },
        EventDispatcherInterface::class => function (ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);
            $mailerSettings = $settings->get('mail');
            $appDomain = $settings->get('app_url');

            $domain = $mailerSettings['domain'];
            $fromAddress = $mailerSettings['from_address'];
            $fromName = $mailerSettings['from_name'];
            $from = "$fromName <$fromAddress>";
            $userRepository = $container->get(UserRepository::class);
            $mailer = $container->get('mailer');
            $dispatcher = new EventDispatcher();
            $dispatcher->subscribeTo(
                AccountRegistered::class, new AccountActivatedEmailListener($appDomain, new Mailer($mailer, $domain, $from), $userRepository),
            );
            $dispatcher->subscribeTo(
                NewPasswordRequested::class, new ForgotPasswordEmailListener($appDomain, new Mailer($mailer, $domain, $from), $userRepository),
            );
            $dispatcher->subscribeTo(
                NewPasswordCreated::class, new NewPasswordEmailListener($appDomain, new Mailer($mailer, $domain, $from), $userRepository),
            );
            return $dispatcher;
        },
    ]);
};
