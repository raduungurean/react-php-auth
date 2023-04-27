<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'api_url' => $_ENV['API_URL'] ?? 'http://localhost/api',
                'app_url' => $_ENV['APP_URL'] ?? 'http://localhost:3000',
                'db' => [
                    'host' => $_ENV['DB_HOST'] ?? 'db',
                    'database' => $_ENV['DB_DATABASE'] ?? 'app_database',
                    'username' => $_ENV['DB_USERNAME'] ?? 'app_user',
                    'password' => $_ENV['DB_PASSWORD'] ?? 'app_password',
                ],
                'jwt' => [
                    'secret' => $_ENV['JWT_SECRET'] ?? 'gH1RmLk5fD8pSdKwN3FtVx3qZzXcBvJ2',
                    'algorithm' => 'HS256'
                ],
                'mail' => [
                    'host' => $_ENV['MAIL_HOST'] ?? 'smtp.example.com',
                    'port' => $_ENV['MAIL_PORT'] ?? '587',
                    'username' => $_ENV['MAIL_USERNAME'] ?? null,
                    'password' => $_ENV['MAIL_PASSWORD'] ?? null,
                    'secure' => $_ENV['MAIL_SECURE'] ?? 'tls',
                    'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'stefan@derby,today',
                    'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'Cards App',
                    'domain' => $_ENV['MAILGUN_DOMAIN'] ?? 'domain',
                    'secret' => $_ENV['MAILGUN_SECRET'] ?? '',
                ],
            ]);
        }
    ]);
};
