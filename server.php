<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$server = new Swoole\HTTP\Server('0.0.0.0', 9501);

$server->on('start', function (Swoole\Http\Server $server) {
    echo "Swoole http server is started at http://0.0.0.0:9501\n";
});

$route = new Route('/', ['action' => 'getIndex'], [], [], '', [], ['GET']);
$routes = new RouteCollection();
$routes->add('index', $route);
$context = new RequestContext('/');
$matcher = new UrlMatcher($routes, $context);

function getIndex(Response $response): void
{
    $response->header('Content-Type', 'text/html');
    $response->end('<html><body><h1>Hello World!</h1></body></html>');
}

$server->on('request', function (Request $request, Response $response) use ($matcher) {

    if ('/favicon.ico' === $request->server['request_uri']) {
        return;
    }

    try {
        $parameters = $matcher->match($request->server['request_uri']);

        call_user_func($parameters['action'], $response);

    } catch (Exception $e) {
        $response->header('Content-Type', 'text/html');
        $response->end("<html><body><h1>Pagina non trovata</h1><h2>{$e->getMessage()}</h2></body></html>");
    }
});

$server->start();
