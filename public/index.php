<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/../templates/', ['cache' => __DIR__ . '/../var/cache/']);
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'table.twig', [
        'table_data' => [
            'headers' => ['Code', 'Name'],
            'rows' => [
                ['RU', 'Российская Федерация'],
                ['BY', 'Республика Беларусь'],
                ['AM', 'Республика Армения'],
                ['SY', 'Сирийская Арабская Республика'],
                ['IR', 'Исламская Республика Иран']
            ]
        ]
    ]);
});

$app->run();
