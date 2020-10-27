<?php

declare(strict_types=1);

namespace SwooleCSVHandler\CsvHandler\Infrastructure\Communication\Http\Controller;

use Co\System;
use Swoole\Http\Request;
use Swoole\Http\Response;

class CsvHandlerController
{
    public function getHandleCsv(Response $response, Request $request): void
    {
        //System::sleep(2);
//        echo 'foo';
//
//        $file = __DIR__.'/../../../../../../../var/file.txt';
//        $current = "John Smith\n";
//        file_put_contents($file, $current);

        //$response->end('<html><body><h1>Ci sono</h1></body></html>');
    }
}
