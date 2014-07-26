<?php

return array(
    'router' => array(
        'routes' => array(
        ),
    ),
    'service_manager' => array(
        'factories' => array(
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Shop\Controller\Index'     => 'Shop\Controller\IndexController',
            'Shop\Controller\Product'   => 'Shop\Controller\ProductController'
        ),
    )
);
