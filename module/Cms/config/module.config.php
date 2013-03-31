<?php

return array(
    'navigation' => array(
    ),
    'di'=>array(
        'instance'  =>array(
            'Application\Service\Acl' => array(
                'parameters' => array(
                    'config' => include __DIR__ . '/acl.config.php'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'page' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/page/:code',
                    'constraints' => array(
                        'code' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller'    =>  'Cms\Controller\Index',
                        'action'        =>  'Index'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Cms\Controller\Index' => 'Cms\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
