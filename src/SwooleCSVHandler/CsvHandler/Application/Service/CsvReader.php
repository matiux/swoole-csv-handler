<?php

declare(strict_types=1);

namespace SwooleCSVHandler\CsvHandler\Application\Service;

use Generator;
use InvalidArgumentException;

class CsvReader
{
    /**
     * @param string $filename
     *
     * @return Generator<int, array, mixed, void>
     */
    public static function iterate(string $filename): Generator
    {
        self::fileExistsOrError($filename);

        $file = fopen($filename, 'r');

        while (($raw_string = fgets($file)) !== false) {
            $row = str_getcsv($raw_string);

            yield $row;
        }
    }

    private static function fileExistsOrError(string $filename): bool
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf('Invalid path [%s]', $filename));
        }

        return true;
    }
}
