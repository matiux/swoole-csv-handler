<?php

declare(strict_types=1);

namespace App;

class Path
{
    public static function src(): string
    {
        return realpath(self::projectRoot().'/src');
    }
    
    public static function projectRoot(): string
    {
        return realpath(__DIR__.'/../../');
    }
}