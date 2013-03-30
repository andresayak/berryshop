<?php

return array(
    'router' => array(
        'routes' => array(
            'category_view' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cat/[/:code]]',
                    'constraints' => array(
                        'code' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller'    =>  'Category',
                        'action'        =>  'View'
                    ),
                ),
            ),
            'product_view' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/product/[/:code]]',
                    'constraints' => array(
                        'code' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller'    =>  'Product',
                        'action'        =>  'View'
                    ),
                ),
            ),
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
