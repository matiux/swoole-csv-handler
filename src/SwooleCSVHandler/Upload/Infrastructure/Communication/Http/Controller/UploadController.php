<?php

declare(strict_types=1);

namespace SwooleCSVHandler\Upload\Infrastructure\Communication\Http\Controller;

use Co\System;
use Exception;
use Swoole\Coroutine\Http\Client;
use Swoole\Http\Request;
use Swoole\Http\Response;

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

    public function getIndex(Response $response, Request $request): void
    {echo 'foo1';
        $client = new Client('127.0.0.1', 80);
        $client->get('/handle');
        $b=$client->getBody();
        System::sleep(4);
        $response->header('Content-Type', 'text/html');
        $response->end('<html><body><h1>Hello World!!</h1></body></html>');
    }
}
