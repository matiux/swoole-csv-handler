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
    'task_enable_coroutine' => true,
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
    public function __invoke(Swoole\Server $server, Swoole\Server\Task $task)
    {
        $data = $task->data;

        $sleep = rand(1, 3);
        echo "->> Sleep {$sleep} sec\n";

        System::sleep($sleep);

        switch ($data->operation) {
            case 'OP1':
                echo "->> Receive new task. ID: {$task->id} | Operazione: \"OP1\". Eseguo...\n";

                break;
            case 'OP2':
                echo "->> Receive new task. ID: {$task->id} | Operazione: \"OP2\". Eseguo...\n";

                break;
            default:
                throw new Exception('Invalid operation');
        }

        // Return the result of executing task
        //$server->finish("\"{$data->operation}\" -> finished");
        $task->finish("\"{$data->operation}\" -> finished");
    }
}

$server->on('finish', function ($server, $task_id, $data) {
    // Handle the result of executing task

    echo "->> Async task {$task_id} result : {$data} \n";
    echo "-------------------------------------------\n";
});

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

        System::sleep(5);

        $op = new stdClass();
        $op->operation = 'OP1';
        $server->task($op);
    } catch (Exception $e) {
        $response->header('Content-Type', 'text/html');
        $response->end("<html><body><h1>Pagina non trovata</h1><h2>{$e->getMessage()}</h2></body></html>");
    }
});

$server->start();
