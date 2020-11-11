<?php

use Swoole\Coroutine\WaitGroup;

Co\run(function() {
    $wg = new WaitGroup();

    $results = [];

    echo 'Prime 2...'."\n";
    $start = microtime(true);

    go(function () use ($wg, &$results) {
        $wg->add();
        co::sleep(0.7);
        $results[] = 'a';
        $wg->done();
    });

    go(function () use ($wg, &$results) {
        $wg->add();
        co::sleep(0.3);
        $results[] = 'b';
        $wg->done();
    });

    $wg->wait();

    $time_elapsed_secs = microtime(true) - $start;
    echo 'Fine prime 2 in '.round($time_elapsed_secs, 3).' ms'."\n";

    echo 'Parte la terza..'."\n";
    $start = microtime(true);
    go(function () use ($wg, &$results) {

        $wg->add();

        co::sleep(0.1);
        $results[] = 'c';
        $wg->done();
    });

    $wg->wait();

    $time_elapsed_secs = microtime(true) - $start;
    echo 'Fine terza in '.round($time_elapsed_secs, 3).' ms'."\n";

    var_dump($results);
});