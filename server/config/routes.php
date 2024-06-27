<?php

use App\Controller\HomePage;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('homepage', '/')
        ->controller([HomePage::class, 'index'])
    ;
};