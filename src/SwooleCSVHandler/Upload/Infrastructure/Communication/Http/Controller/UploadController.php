<?php

declare(strict_types=1);

namespace SwooleCSVHandler\Upload\Infrastructure\Communication\Http\Controller;

use Exception;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class UploadController
{
    public function postUpload(Response $response, Request $request): void
    {
        $uploadedFileNameKey = 'file_to_upload';

        if (!array_key_exists($uploadedFileNameKey, $request->files)) {
            throw new Exception(sprintf('Chiave %s per upload mancante', $uploadedFileNameKey));
        }

        $file = $request->files[$uploadedFileNameKey];

        $destinationPath = __DIR__.'/../../../../../../../var/upload';
        $result = move_uploaded_file($file['tmp_name'], $destinationPath.'/'.$file['name']);

        if ($result) {
            $response->header('Content-Type', 'text/html');
            $response->status(200);
            $response->end('<html><body><h1>File carrricato</h1></body></html>');
        } else {
            $response->header('Content-Type', 'text/html');
            $response->status(500);
            $response->end('<html><body><h1>File NON carrricato</h1></body></html>');
        }
    }

    public static function getIndex(Response $response, Request $request): void
    {


//        $server = new Server('127.0.0.1', 9501);
//        $server->set([
//            'task_worker_num' => 1,
//        ]);
//
//        $server->on('receive', function($server, $fd, $from_id, $data){
//
//            // Send data to the task worker process
//            $task_id = $server->task($data);
//            echo "Dispatch async task: id = {$task_id}\n";
//
//            // Send data to the client
//            $server->send($fd, "Server: " . $data);
//        });

        //$server->start();

        $response->header('Content-Type', 'text/html');
        $response->end('<html><body><h1>Hello World!!!!</h1></body></html>');
    }
}
