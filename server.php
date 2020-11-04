<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use App\RouterMatcher;
use Co\System;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use SwooleCSVHandler\Common\Application\Util\CEcho;

$server = new Swoole\HTTP\Server('0.0.0.0', 9501);
$server->set([
    'task_worker_num' => 1,
    'task_enable_coroutine' => true,
    'package_max_length' => 1024 * 1024 * 20, // 20Mb
    'document_root' => '/var/www/app',
    'enable_static_handler' => true,
]);

$server->on('start', function (Swoole\Http\Server $server) {
    CEcho::echon('|->> Swoole http server is started at http://0.0.0.0:9501', 'green');
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
        CEcho::echon("|->> Sleep {$sleep} sec", 'green');

        System::sleep($sleep);

        switch ($data->operation) {
            case 'OP1':
                CEcho::echon("|->> Receive new task. ID: {$task->id} | Operazione: \"OP1\". Eseguo...", 'green');

                break;
            case 'OP2':
                CEcho::echon("|->> Receive new task. ID: {$task->id} | Operazione: \"OP2\". Eseguo...", 'green');

                break;
            default:
                throw new Exception('Invalid operation');
        }

        // Return the result of executing task
        //$server->finish("\"{$data->operation}\" -> finished");
        $task->finish("\"{$data->operation}\" -> finished");
    }
}

$server->on('finish', function (Server $server, $task_id, $data) {
    // Handle the result of executing task
    CEcho::echon("|->> Async task {$task_id} result : {$data}", 'green');
    CEcho::echon('-------------------------------------------', 'green');
});

$server->on('task', new TaskWorker());

$server->on('request', function (Request $request, Response $response) use ($server) {
    $response->header('Access-Control-Allow-Origin', '*');

    try {
        $routeMatcher = new RouterMatcher();
        $parameters = $routeMatcher->match($request->server['request_uri'], $request->server['request_method']);

        call_user_func($parameters['_controller'], $response, $request, $server);

        System::sleep(5);

        $op = new stdClass();
        $op->operation = 'OP1';
        $server->task('..');
    } catch (Exception $e) {
        $response->header('Content-Type', 'text/html');
        $response->end("<html><body><h1>Pagina non trovata</h1><h2>{$e->getMessage()}</h2></body></html>");
    }
});

$server->start();
