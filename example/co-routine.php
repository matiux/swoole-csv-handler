<?php

declare(strict_types=1);

use Co\Http\Client;

function caller(): array
{
    $data = ['power', 'green', 'red', 'dog'];
    //$data = [0.4, 0.2];
    //$data = ['www.sideaita.it', 'www.amazon.it', 'www.bing.it', 'www.matteogalacci.it', 'www.google.it'];

    $responses = [];

    Co\run(function () use ($data, &$responses) {
        foreach ($data as $i => $val) {
            go(function () use ($i, $val, &$responses) {
                echo "Call: {$i}) ".$val."\n";
                $parts = parse_url('https://api.publicapis.org/entries');
                $client = new Client($parts['host'], 443, true);
                $path = sprintf('%s?description=%s', $parts['path'], $val);
                $start = microtime(true);
                $client->get($path);
                $time_elapsed_secs = microtime(true) - $start;
                $count = (int) json_decode($client->body, true)['count'];
                $responses[] = "{$i}: ".$path.' - count:'.$count.' - ms: '.round($time_elapsed_secs, 3);
                $client->close();
            });
        }
    });

    return $responses;
}

echo 'Chiamo...'."\n";
$res = caller();
echo 'Chiamata finita'."\n";

echo 'Stampa...'."\n";
print_r($res);
echo 'Stampa finita'."\n";



//function caller(): array
//{
//    $results = [];
//
//    Co\run(function () use (&$results) {
//        $data = ['power', 'green', 'red', 'dog'];
//
//        foreach ($data as $i => $val) {
//            go(function () use (&$results, $val) {
//                $host = 'api.publicapis.org';
//                $query = '/entries?description='.$val;
//                $client = new Client($host, 443, true);
//                $start = microtime(true);
//                $client->get($query);
//                $time_elapsed_secs = microtime(true) - $start;
//                $count = (int) json_decode($client->body, true)['count'];
//                $results[] = $host.$query.' - count: '.$count.' - ms: '.round($time_elapsed_secs, 3);
//                $client->close();
//            });
//        }
//    });
//
//    return $results;
//}
//
//var_dump(caller());
