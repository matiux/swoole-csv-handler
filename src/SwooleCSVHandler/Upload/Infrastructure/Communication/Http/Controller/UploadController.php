<?php

declare(strict_types=1);

namespace SwooleCSVHandler\Upload\Infrastructure\Communication\Http\Controller;

use Exception;
use stdClass;
use Swoole\Coroutine\System;
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

        $fileName = self::generateFileName($file);
        $result = move_uploaded_file($file['tmp_name'], $fileName);

        if ($result) {
            $response->header('Content-Type', 'application/json');
            $response->status(200);
            $response->end(json_encode(['uploaded_file' => realpath($fileName)]));

//            System::sleep(5);
//
//            $op = new stdClass();
//            $op->operation = 'OP1';
//            $op->fd = 1;
//            $op->md5 = '';
//            $server->task($op);
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

    private static function generateFileName(array $file): string
    {
        $destinationPath = __DIR__.'/../../../../../../../var/upload';

        $md5 = md5_file($file['tmp_name']);
        $time = time();

        $fileName = sprintf('%s/%s-%s_%s', $destinationPath, $md5, $time, $file['name']);

        return $fileName;
    }
}
