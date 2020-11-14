<?php

declare(strict_types=1);

namespace SwooleCSVHandler\Entries\Infrastructure\Communication\Http\Controller;

use App\Path;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class EntriesController
{
    private static array $entries = [];

    public static function getEntries(Response $response, Request $request, Server $server): void
    {
        self::loadEntries();
        $queryString = self::extractQueryStringIfExists($request);
        $filteredEntries = self::filterEntries($queryString);
        $filteredEntriesResponse = self::prepareResponse($filteredEntries);
        $response->end(json_encode($filteredEntriesResponse));
    }

    private static function loadEntries(): void
    {
        if (empty(self::$entries)) {
            self::$entries = json_decode(file_get_contents(Path::projectRoot().'/entries.json'), true);
        }
    }

    private static function extractQueryStringIfExists(Request $request): array
    {
        if (array_key_exists('query_string', $request->server)) {
            parse_str($request->server['query_string'], $queryString);

            return $queryString;
        }

        return [];
    }

    private static function filterEntries(array $queryString): array
    {
        if (empty($queryString)) {
            return self::$entries;
        }

        $path = sprintf('/(%s)/', $queryString['description']);

        $e = [];

        foreach (self::$entries as $key => $entry) {
            $match = preg_match($path, $entry['Description']);

            if (1 === $match) {
                $e[] = self::$entries[$key];
            }
        }

        return $e;
    }

    private static function prepareResponse(array $filteredEntries): array
    {
        $response = [
            'count' => count($filteredEntries),
            'entries' => $filteredEntries,
        ];

        return $response;
    }
}
