<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use App\RouterMatcher;
use Co\System;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server\Task;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use SwooleCSVHandler\Common\Application\Util\CEcho;

$server = new Server('0.0.0.0', 9501);
$server->set([
    'worker_num' => 1,
    'task_worker_num' => 1,
    'task_enable_coroutine' => true,
    'package_max_length' => 1024 * 1024 * 20, // 20Mb
    'document_root' => '/var/www/app',
    'enable_static_handler' => true,
]);

$server->on('start', function (Server $server) {
    swoole_set_process_name('|->> Swoole server');

    CEcho::echon('|->> Swoole http server is started at http://0.0.0.0:9501', 'green');
});

$server->on('workerstart', function (Server $server) {
    swoole_set_process_name('|->> Swoole worker');

    CEcho::echon('|->> Swoole worker is started', 'green');
});

$server->on('managerstart', function (Server $server) {
    swoole_set_process_name('|->> Swoole mananger');

    CEcho::echon('|->> Swoole manager is started', 'green');
});

$server->on('open', function (Server $server, Request $request) {
    //$i = $server->getClientInfo($request->fd);

    CEcho::echon("|->> Web socket server is opened on fd {$request->fd}", 'green');
    $server->push($request->fd, json_encode(['msg', "Web socket server is opened on fd {$request->fd}"]));
});

$server->on('message', function (Server $server, Frame $frame) {
    CEcho::echon("|->> received message: {$frame->data}", 'green');

    $job = new stdClass();
    $job->socketFd = $frame->fd;
    $job->body = json_decode($frame->data, true);

    $server->task($job);

    $msg = sprintf('Elaborazione iniziata: %s', (new DateTime())->format(DateTime::RFC3339_EXTENDED));

    $server->push($frame->fd, json_encode(['msg' => $msg, 'fd' => $frame->fd]));
});

$server->on('receive', function ($server, $fd, $from_id, $data) {
    CEcho::echon('|->> onReceive event', 'green');

//    $server->task($data);
    // Send data to the task worker process
//    $task_id = $server->task($data);
//    echo "Dispatch async task: id = {$task_id}\n";
//
//    // Send data to the client
//    $server->send($fd, "Server: " . $data);
});

class TaskWorker
{
    public function __invoke(Server $server, Task $task)
    {
        $data = $task->data;

        $body = $data->body;
        $socketFd = $data->socketFd;

        $server->push($socketFd, json_encode(['uploaded_file' => $body['msg'], 'fd' => $socketFd]));
//        foreach ($server->connection_list(0) as $foo) {
//            var_dump($foo);
//        }
//
//        $server->push($data->fd, json_encode(['msg', 'Task is running']));
//        CEcho::echon("|->> Fd: {$data->fd}", 'green');

        $sleep = rand(2, 3);
        CEcho::echon("|->> Task sleep {$sleep} sec", 'green');
        System::sleep($sleep);

//        switch ($data->operation) {
//            case 'OP1':
//                CEcho::echon("|->> Receive new task. ID: {$task->id} | Operazione: \"OP1\". Eseguo...", 'green');
//
//                break;
//            case 'OP2':
//                CEcho::echon("|->> Receive new task. ID: {$task->id} | Operazione: \"OP2\". Eseguo...", 'green');
//
//                break;
//            default:
//                throw new Exception('Invalid operation');
//        }

        // Return the result of executing task
        //$server->finish("\"{$data->operation}\" -> finished");
//        $task->finish("\"{$data->operation}\" -> finished");
        $task->finish('Task finished');
    }
}

$server->on('finish', function (Server $server, $task_id, $data) {
    // Handle the result of executing task
    CEcho::echon("|->> Async task {$task_id} result : {$data}", 'green');
    CEcho::echon('-------------------------------------------', 'green');
});

$server->on('task', function ($server, $task) {
    swoole_set_process_name('         |->> Swoole task worker');

    $tw = new TaskWorker();
    $tw($server, $task);
});

$server->on('request', function (Request $request, Response $response) use ($server) {
    $response->header('Access-Control-Allow-Origin', '*');

    try {
        $routeMatcher = new RouterMatcher();
        $parameters = $routeMatcher->match($request->server['request_uri'], $request->server['request_method']);

        call_user_func($parameters['_controller'], $response, $request, $server);
    } catch (Exception $e) {
        $response->header('Content-Type', 'text/html');
        $response->end("<html><body><h1>Pagina non trovata</h1><h2>{$e->getMessage()}</h2></body></html>");
    }
});

$server->start();
