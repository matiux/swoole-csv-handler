<?php

declare(strict_types=1);

namespace SwooleCSVHandler\Common\Application\Util;

use Wujunze\Colors;

class CEcho
{
    public static function echon($string, $foreground_color = null, $background_color = null)
    {
        $colors = new Colors();

        echo $colors->getColoredString($string, $foreground_color, $background_color, true);
    }
}
