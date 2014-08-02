<?php

return array(
    'navigation' => include __DIR__ . '/navigation.config.php',
    'acl' => include __DIR__ . '/acl.config.php',
    'module_layouts' => array(
        'Admin' => 'layout/admin',
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/admin[/:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                         'controller'   => 'Index',
                        'action'        => 'index',
                    ),
                ),
            ),
            'admin-lib' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/admin/lib',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                         'controller'   => 'Lib',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'sub' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '[/:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                 '__NAMESPACE__' => 'Admin\Controller\Lib',
                                'controller'   => 'Index',
                               'action'        => 'index',
                            )
                        )
                    ),
                ),
            ),
            'admin-system' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/admin/system',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'System',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'sub' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '[/:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                 '__NAMESPACE__' => 'Admin\Controller\System',
                                'controller'   => 'Index',
                               'action'        => 'index',
                            )
                        )
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation/admin' => new Application\Service\NavigationFactory('admin'),
         ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index'    =>  'Admin\Controller\IndexController',
            'Admin\Controller\Users'    =>  'Admin\Controller\UsersController',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/admin'           => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables'    =>  array(
            'date'  =>  'Admin\View\Helper\Date',
            'systemExport' => 'Admin\View\Helper\SystemExport',
        ),
        'factories' =>  array(
        )
    ),
);
