<?php

declare(strict_types=1);

namespace SwooleCSVHandler\Upload\Infrastructure\Communication\Http\Controller;

use Exception;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class UploadController
{
    public static function postUpload(Response $response, Request $request, Server $server): void
    {
        $uploadedFileNameKey = 'file_to_upload';

        if (!array_key_exists($uploadedFileNameKey, $request->files)) {
            throw new Exception(sprintf('Chiave %s per upload mancante', $uploadedFileNameKey));
        }

        $file = $request->files[$uploadedFileNameKey];

        $destinationPath = __DIR__.'/../../../../../../../var/upload';
        $result = move_uploaded_file($file['tmp_name'], $destinationPath.'/'.$file['name']);

        if ($result) {
            $response->header('Content-Type', 'application/json');
            $response->status(200);
            $response->end(json_encode(['msg' => 'File carrricato']));
        } else {
            $response->header('Content-Type', 'application/json');
            $response->status(500);
            $response->end(json_encode(['msg' => 'File NON carrricato']));
        }
    }

    public static function getIndex(Response $response, Request $request, Server $server): void
    {
        $response->header('Content-Type', 'application/json');
        $body = json_encode(['msg' => 'Hello World!!!!']);
        $response->end($body);
    }
}
