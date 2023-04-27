<?php

declare(strict_types=1);

use App\Domain\Card\CardRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Card\DbCardRepository;
use App\Infrastructure\Persistence\User\DbUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CardRepository::class => \DI\autowire(DbCardRepository::class),
        UserRepository::class => \DI\autowire(DbUserRepository::class),
    ]);
};
