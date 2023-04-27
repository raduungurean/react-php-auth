<?php

declare(strict_types=1);

use App\Application\Actions\Card\ListCardsAction;
use App\Application\Actions\Card\ViewCardAction;
use App\Application\Actions\User\ActivateAccountAction;
use App\Application\Actions\User\CheckForgotTokenAction;
use App\Application\Actions\User\ForgotPasswordAction;
use App\Application\Actions\User\LoginAction;
use App\Application\Actions\User\LogoutAction;
use App\Application\Actions\User\NewPasswordAction;
use App\Application\Actions\User\ReauthenticateAction;
use App\Application\Actions\User\RegisterAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello home!');
        return $response;
    });

    $app->group('/api', function (RouteCollectorProxy $group) use ($app) {
        $group->post('/login', LoginAction::class);
        $group->post('/logout', LogoutAction::class);
        $group->post('/register', RegisterAction::class);
        $group->post('/reauthenticate', ReauthenticateAction::class);
        $group->post('/activate', ActivateAccountAction::class);
        $group->post('/forgot-password', ForgotPasswordAction::class);
        $group->post('/new-password', NewPasswordAction::class);
        $group->post('/check-forgot-token', CheckForgotTokenAction::class);
        $group->group('/cards', function (Group $groupCards) {
            $groupCards->get('', ListCardsAction::class);
            $groupCards->get('/{id}', ViewCardAction::class);
        })->add($app->getContainer()->get('jwtAuthMiddleware'));
    });
};
