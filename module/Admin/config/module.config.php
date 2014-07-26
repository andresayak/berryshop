<?php

return array(
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
            'menu\admin' => 'Admin\Navigation\Service\AdminNavigationFactory',
         ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index'            =>  'Admin\Controller\IndexController',
            'Admin\Controller\Users'            =>  'Admin\Controller\UsersController',
            'Admin\Controller\Server'           =>  'Admin\Controller\ServerController',
            'Admin\Controller\Region'           =>  'Admin\Controller\RegionController',
            'Admin\Controller\City'             =>  'Admin\Controller\CityController',
            'Admin\Controller\Event'            =>  'Admin\Controller\EventController',
            'Admin\Controller\Transport'        =>  'Admin\Controller\TransportController',
            'Admin\Controller\Lib'              =>  'Admin\Controller\LibController',
            'Admin\Controller\Lib_landscape'    =>  'Admin\Controller\Lib\LandscapeController',
            'Admin\Controller\Lib_attr'         =>  'Admin\Controller\Lib\AttrController',
            'Admin\Controller\Lib_object'       =>  'Admin\Controller\Lib\ObjectController',
            'Admin\Controller\Lib_citynext'     =>  'Admin\Controller\Lib\CitynextController',
            'Admin\Controller\Lib_quest'        =>  'Admin\Controller\Lib\QuestController',
            'Admin\Controller\Lib_leveluser'    =>  'Admin\Controller\Lib\LevelUserController',
            'Admin\Controller\Lib_levelalliance'=>  'Admin\Controller\Lib\LevelAllianceController',
            'Admin\Controller\Lib_npc'=>  'Admin\Controller\Lib\NpcController',
            'Admin\Controller\System'           =>  'Admin\Controller\SystemController',
            'Admin\Controller\System_news'      =>  'Admin\Controller\System\NewsController',
            'Admin\Controller\System_cache'     =>  'Admin\Controller\System\CacheController',
            'Admin\Controller\System_export'    =>  'Admin\Controller\System\ExportController',
            'Admin\Controller\System_feedback'  =>  'Admin\Controller\System\FeedbackController',
            'Admin\Controller\Market'           =>  'Admin\Controller\MarketController',
            'Admin\Controller\Shop'             =>  'Admin\Controller\ShopController',
            'Admin\Controller\Fountain'         =>  'Admin\Controller\FountainController',
            'Admin\Controller\Alliance'         =>  'Admin\Controller\AllianceController',
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
    'acl' => array(
        'resources'=>array(
            'admin' => array(
                'resource' => 'admin',
                'children' => array(
                    'admin-lib' => array(
                        'resource' => 'admin-lib',
                        'children' => array(
                            'admin-lib/sub' => array(
                                'resource' => 'admin-lib/sub',
                                'children' => array(
                                )
                            )
                        )
                    ),
                    'admin-system' => array(
                        'resource' => 'admin-system',
                        'children' => array(
                            'admin-system/sub' => array(
                                'resource' => 'admin-system/sub',
                                'children' => array(
                                )
                            )
                        )
                    )
                ),
            ),
        ),
    ),
    'navigation'    =>  array(
        'admin' => array(
            array(
                'label' => 'Dashboard',
                'route' => 'admin',
                'params'   =>  array(
                    'controller'=>'Index'
                )
            ),
            array(
                'label' => 'Users',
                'route' => 'admin',
                'params'   =>  array(
                    'controller'=>'user'
                )
            ),
            array(
                'label' => 'Products',
                'route' => 'admin',
                'params' => array(
                    'controller' => 'product'
                )
            ),
            array(
                'label' => 'Order',
                'route' => 'admin',
                'params' => array(
                    'controller' => 'order'
                )
            ),
            array(
                'label' => 'Cms',
                'route' => 'admin',
                'params'   =>  array(
                    'controller'=>'lib'
                ),
                'pages'=>array(
                    array(
                        'label' => 'News',
                        'route' => 'admin',
                        'params'   =>  array(
                            'controller'=>'object'
                        ),
                        'route'    =>  'admin-cms/sub'
                    ),
                    array(
                        'label' => 'Feedbacks',
                        'route' => 'admin',
                        'params'   =>  array(
                            'controller'=>'feedback'
                        ),
                        'route'    =>  'admin-system/sub'
                    ),
                )
            ),
            array(
                'label' => 'System',
                'route' => 'admin',
                'params'   =>  array(
                    'controller'=>'system'
                ),
                'pages'=>array(
                    array(
                        'label' => 'Caches',
                        'route' => 'admin',
                        'params'   =>  array(
                            'controller'=>'cache'
                        ),
                        'route'    =>  'admin-system/sub'
                    ),
                    array(
                        'label' => 'Import / Export',
                        'route' => 'admin',
                        'params'   =>  array(
                            'controller'=>'export'
                        ),
                        'route'    =>  'admin-system/sub'
                    ),
                )
            ),
        ),
    )
);
