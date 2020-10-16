<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use App\RouterMatcher;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\Routing\RequestContext;

$server = new Swoole\HTTP\Server('0.0.0.0', 9501);
$server->set(['package_max_length' => 1024 * 1024 * 20]); // 20Mb
$server->on('start', function (Swoole\Http\Server $server) {
    echo "Swoole http server is started at http://0.0.0.0:9501\n";
});


$server->on('request', function (Request $request, Response $response) {
    if ('/favicon.ico' === $request->server['request_uri']) {
        return;
    }

    try {
        $context = new RequestContext($request->server['request_uri'], $request->server['request_method']);
        $routeMatcher = new RouterMatcher($context);

        $parameters = $routeMatcher->match($request->server['request_uri']);

        call_user_func($parameters['_controller'], $response, $request);
    } catch (Exception $e) {
        $response->header('Content-Type', 'text/html');
        $response->end("<html><body><h1>Pagina non trovata</h1><h2>{$e->getMessage()}</h2></body></html>");
    }
});

$server->start();
