<?php

namespace App;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;

class RouterMatcher
{
    private UrlMatcher $matcher;

    public function __construct(RequestContext $context)
    {
        $fileLocator = new FileLocator([__DIR__.'/../../config/routes']);
        $loader = new YamlFileLoader($fileLocator);
        $routes = $loader->load('routes.yaml');

        $this->matcher = new UrlMatcher($routes, $context);
    }

    public function match(string $pathInfo): array
    {
        return $this->matcher->match($pathInfo);
    }
}
