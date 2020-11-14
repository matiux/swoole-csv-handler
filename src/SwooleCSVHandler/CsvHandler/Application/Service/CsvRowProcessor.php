<?php

declare(strict_types=1);

namespace SwooleCSVHandler\CsvHandler\Application\Service;

use Co\Http\Client;

class CsvRowProcessor
{
    /**
     * @var string[]
     */
    private array $keys = ['power', 'green', 'red', 'dog'];

    /** @psalm-var list<array{url: string, count: int, size: int, ms: float}> */
    private array $responses = [];

    /**
     * @param array{id: string, firstname: string, lastname: string, email: string, url: string, descriptions: string, calls_number: int} $row
     *
     * @return list<array{url: string, count: int, size: int, ms: float}>
     */
    public function execute(array $row): array
    {
        $descriptions = explode(';', trim($row['descriptions']));

        $url = (string) $row['url'];
        $this->resetResponse();
        \Co\run(function () use ($descriptions, $url) {
            foreach ($descriptions as $i => $description) {
                go(function () use ($url, $i, $description) {
                    echo "Call: {$i}) ".$url.'='.$description."\n";

                    $this->callUrl($url, $description);
                });
            }
        });

        return $this->responses;
    }

    private function resetResponse(): void
    {
        $this->responses = [];
    }

    /**
     * @param string $url
     * @param string $description
     */
    private function callUrl(string $url, string $description): void
    {
        $host = parse_url($url)['host'];
        $client = new Client($host,9501);
        $url = sprintf('%s=%s', $url, $description);
        $start = microtime(true);
        $client->get($url);
        $time_elapsed_secs = microtime(true) - $start;

        $client->close();

        $this->responses[] = $this->buildResponse($url, $client, $time_elapsed_secs);
    }

    /**
     * @param string $url
     * @param Client $client
     * @param float  $time_elapsed_secs
     *
     * @return array{url: string, count: int, size: int, ms: float}
     */
    private function buildResponse(string $url, Client $client, float $time_elapsed_secs): array
    {
        $response = [];

        $response['url'] = $url;
        /** @var array{count: int} $body */
        $body = json_decode((string) $client->body, true);

        //$response['count'] = (int) $body['meta']['pagination']['total'];
        $response['count'] = (int) $body['count'];
        $response['size'] = (int) strlen((string) $client->body);
        $response['ms'] = round($time_elapsed_secs, 5);

        return $response;
    }
}
