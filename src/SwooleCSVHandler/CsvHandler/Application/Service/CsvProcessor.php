<?php

declare(strict_types=1);

namespace SwooleCSVHandler\CsvHandler\Application\Service;

use Co\WaitGroup;

class CsvProcessor
{
    private CsvRowProcessor $csvRowProcessor;
    private array $columns;

    public function __construct(CsvRowProcessor $csvRowProcessor)
    {
        $this->csvRowProcessor = $csvRowProcessor;
    }

    public function execute(string $csvPath): array
    {
        $res = [];
//        \Co\run(function () use($csvPath) {
//            $wg = new WaitGroup();

            foreach (CsvReader::iterate($csvPath) as $i => $row) {
                if (0 == $i) {
                    $this->setColumns($row);

                    continue;
                }

                //go(function () use ($row, &$res) {
//                    $wg->add();
                    $res[] = $this->csvRowProcessor->execute($this->addRowColumns($row));
//                    $wg->done();
                //});
            }

//            $wg->wait();
//        });

        return $res;
    }

    private function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    private function addRowColumns($row): array
    {
        return array_combine($this->columns, $row);
    }
}