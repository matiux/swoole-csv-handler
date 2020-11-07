<?php

declare(strict_types=1);

namespace SwooleCSVHandler\CsvHandler\Application\Service;

use Swoole\Coroutine\Http\Client;
use Swoole\Coroutine\WaitGroup;

class CsvRowProcessor
{
    /**
     * @var string[]
     */
    private array $keys = ['red', 'green', 'power'];

    /**
     * @param array{id: string, firstname: string, lastname: string, email: string, url: string, calls_number: int} $row
     *
     * @return list<array{url: string, count: int, size: int}>
     */
    public function execute(array $row): array
    {
        $callsNumber = $row['calls_number'];
        $url = $row['url'];

        /** @var list<array{url: string, count: int, size: int}> $responses */
        $responses = [];
        \Co\run(function () use ($callsNumber, $url, &$responses) {
            $wg = new WaitGroup();

            for ($i = 0; $i < $callsNumber; ++$i) {
                go(function () use ($url, &$responses, $wg) {
                    $wg->add();
                    $responses[] = $this->callUrl($url);

                    $wg->done();
                });

                //$wg->wait(1);
            }
        });

        return $responses;
    }

    /**
     * @param string $url
     *
     * @return array{url: string, count: int, size: int}
     */
    private function callUrl(string $url): array
    {
        $parts = parse_url($url);

        $response = [];

        $client = new Client($parts['host'], 443, true);
        $path = sprintf('%s?description=%s', $parts['path'], $this->keys[rand(0, 2)]);
        $client->get($path);
        $response['url'] = $url.$path;
        $response['count'] = (int) json_decode($client->body, true)['count'];
        $response['size'] = (int) strlen($client->body);
        $client->close();

        return $response;
    }
}

/**
 *     go(function () use ($key) {.
 * $client = new \Co\Http\Client('api.publicapis.org', 443, true);
 * $client->get("/entries?description={$key}");
 * echo $client->host."/entries?description={$key}"."\n";
 * echo json_decode($client->body, true)['count']."\n";
 * $client->close();
 * });.
 */
