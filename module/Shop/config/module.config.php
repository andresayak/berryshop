<?php

return array(
    'router' => array(
        'routes' => array(
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Category\Table' =>  function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Shop\Model\Category\Table($sm, $dbAdapter);
            },
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Shop\Controller\Index'     => 'Shop\Controller\IndexController',
            'Shop\Controller\Product'   => 'Shop\Controller\ProductController'
        ),
    )
);
