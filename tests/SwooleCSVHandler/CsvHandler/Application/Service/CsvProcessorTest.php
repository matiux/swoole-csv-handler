<?php

namespace Tests\SwooleCSVHandler\CsvHandler\Application\Service;

use SwooleCSVHandler\CsvHandler\Application\Service\CsvProcessor;
use PHPUnit\Framework\TestCase;
use SwooleCSVHandler\CsvHandler\Application\Service\CsvRowProcessor;
use Tests\Util\Path;

class CsvProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function should_process_csv_file(): void
    {
        $file = Path::projectRoot().'/Resources/small.csv';

        $csvProcessor = new CsvProcessor(new CsvRowProcessor());
        $res = $csvProcessor->execute($file);

        self::assertCount(10, $res);
    }
}