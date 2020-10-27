<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use App\RouterMatcher;
use Co\System;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\Routing\RequestContext;

$server = new Swoole\HTTP\Server('0.0.0.0', 9501);
$server->set([
    'task_worker_num' => 1,
    'package_max_length' => 1024 * 1024 * 20, // 20Mb
]);

$server->on('start', function (Swoole\Http\Server $server) {
    echo "->> Swoole http server is started at http://0.0.0.0:9501\n";
});

//$server->on('receive', function ($server, $fd, $from_id, $data) {
//    echo '->> AA';
//    $server->task($data);
//    // Send data to the task worker process
////    $task_id = $server->task($data);
////    echo "Dispatch async task: id = {$task_id}\n";
////
////    // Send data to the client
////    $server->send($fd, "Server: " . $data);
//});

class TaskWorker
{
    public function __invoke($server, $taskId, $fromId, $data)
    {
        echo "->> Receive new task \"$data\", id : {$taskId}. Eseguo...\n";

        // Return the result of executing task
        $server->finish("\"$data\" -> finished");
    }
}

$server->on('task', new TaskWorker());

$server->on('request', function (Request $request, Response $response) use ($server) {
    if ('/favicon.ico' === $request->server['request_uri']) {
        return;
    }

    try {
        $context = new RequestContext($request->server['request_uri'], $request->server['request_method']);
        $routeMatcher = new RouterMatcher($context);

        $parameters = $routeMatcher->match($request->server['request_uri']);

        call_user_func($parameters['_controller'], $response, $request);

        System::sleep(3);

        $server->task('OPERAZIONE');

    } catch (Exception $e) {
        $response->header('Content-Type', 'text/html');
        $response->end("<html><body><h1>Pagina non trovata</h1><h2>{$e->getMessage()}</h2></body></html>");
    }
});


$server->on('finish', function($server, $task_id, $data){
    // Handle the result of executing task

    echo "->> Async task {$task_id} result : {$data} \n";
});

$server->start();