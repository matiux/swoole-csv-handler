<?php

declare(strict_types=1);

namespace Tests\SwooleCSVHandler\CsvHandler\Application\Service;

use PHPUnit\Framework\TestCase;
use SwooleCSVHandler\CsvHandler\Application\Service\CsvRowProcessor;

class CsvRowProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function should_process_csv_row(): void
    {
        $row = [
            'id' => '36f5b67c-7b35-4e96-95d7-2491cec06bc4',
            'firstname' => 'Orelia',
            'lastname' => 'Magnolia',
            'email' => 'orelia.magnolia@yopmail.com',
            'url' => 'https://api.publicapis.org/entries',
            'calls_number' => 4,
        ];

        $csvRowProcessor = new CsvRowProcessor();
        $resp = $csvRowProcessor->execute($row);

        self::assertCount(4, $resp);

        var_dump($resp);
    }
}
