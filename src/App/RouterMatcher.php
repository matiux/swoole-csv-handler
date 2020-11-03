<?php

namespace App;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RouteCollection;

class RouterMatcher
{
    private RouteCollection $routes;

    public function __construct()
    {
        $fileLocator = new FileLocator([__DIR__.'/../../config/routes']);
        $loader = new YamlFileLoader($fileLocator);
        $this->routes = $loader->load('routes.yaml');
    }

    public function match(string $requestUri, string $requestMethod): array
    {
        $context = new RequestContext('/', $requestMethod);

        $matcher = new UrlMatcher($this->routes, $context);

        return $matcher->match($requestUri);
    }
}
