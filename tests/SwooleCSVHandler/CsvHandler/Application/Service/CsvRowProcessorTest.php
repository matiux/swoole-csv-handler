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
            'url' => 'https://gorest.co.in/public-api/products?description',
            'descriptions' => 'cras;centum;versus;temp',
            'calls_number' => 4,
        ];

        $csvRowProcessor = new CsvRowProcessor();
        $resp = $csvRowProcessor->execute($row);

        self::assertCount(4, $resp);
        self::assertMsAreOrdered($resp);
        self::assertUrlCalledInDifferentOrder($resp);
    }

    private static function assertMsAreOrdered(array $resp): void
    {
        $originalMs = array_column($resp, 'ms');
        $sortedMs = array_values($originalMs);

        sort($sortedMs);

        self::assertSame($originalMs, $sortedMs);
    }

    private static function assertUrlCalledInDifferentOrder(array $resp): void
    {
        $calledUrls = array_column($resp, 'url');

        $urlInOriginalOrderByCSV = [
            'https://gorest.co.in/public-api/products?description=cras',
            'https://gorest.co.in/public-api/products?description=centum',
            'https://gorest.co.in/public-api/products?description=versus',
            'https://gorest.co.in/public-api/products?description=temp"',
        ];

        self::assertNotSame($calledUrls, $urlInOriginalOrderByCSV);
    }
}
