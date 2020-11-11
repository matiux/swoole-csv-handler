<?php

declare(strict_types=1);

namespace Tests\SwooleCSVHandler\CsvHandler\Application\Service;

use PHPUnit\Framework\TestCase;
use SwooleCSVHandler\CsvHandler\Application\Service\CsvReader;
use Tests\Util\Path;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CsvReaderTest extends TestCase
{
    /**
     * @test
     */
    public function should_iterate_csv_file(): void
    {
        $file = Path::projectRoot().'/Resources/small.csv';

        $rowsCounter = 0;

        foreach (CsvReader::iterate($file) as $row) {
            ++$rowsCounter;
            self::assertNotEmpty($row);
            self::assertIsArray($row);
            self::assertCount(7, $row);
        }

        self::assertSame(11, $rowsCounter);
    }
}
