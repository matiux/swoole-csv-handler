<?php

declare(strict_types=1);

namespace Tests\Util;

class Path
{
    public static function test(): string
    {
        return realpath(__DIR__.'/..');
    }

    public static function projectRoot(): string
    {
        return realpath(self::test().'/../');
    }
}
