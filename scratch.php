<?php

declare(strict_types=1);

$data = ['red', 'green', 'power'];

foreach ($data as $key) {
    go(function () use ($key) {
        $client = new \Co\Http\Client('api.publicapis.org', 443, true);
        $client->get("/entries?description={$key}");
        echo $client->host."/entries?description={$key}"."\n";
        echo json_decode($client->body, true)['count']."\n";
        $client->close();
    });
}
