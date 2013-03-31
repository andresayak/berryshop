<?php

return array(
    'navigation' => include __DIR__ . '/navigation.config.php',
    'di' => array(
        'instance' =>array(
            'Application\Service\Acl' => array(
                'parameters' => array(
                    'config' => include __DIR__ . '/acl.config.php'
                )
            )
        )
    ),
    'router' => array(
        'routes' => include __DIR__ . '/route.config.php'
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'  => 'Application\Controller\IndexController',
            'Application\Controller\Auth'   => 'Application\Controller\AuthController',
            'Application\Controller\Admin'   => 'Application\Controller\AdminController',
            'Application\Controller\Admin\Users'   => 'Application\Controller\Admin_UsersController'
        ),
    ),
     'service_manager' => array(
        'factories' => array(
            'navigation' => new Application\Service\NavigationFactory(),
            'navigation/footer' => new Application\Service\NavigationFactory('footer'),
            'navigation/admin' => new Application\Service\NavigationFactory('admin'),
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Auth_Service' => function($sm){
                $service = new Application\Service\Auth($sm->get('User_Table'));
                $service->setAcl($sm->get('Application\Service\Acl'));
                return $service;
             },
             'Themes_Service' => function($sm){
                $service = new Application\Service\Themes($sm->get('User_Table'));
                return $service;
             },
             'User_Table' =>  function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $table = new Application\Model\User\Table($dbAdapter);
                return $table;
            }
        ),
        
    ),
    'view_helpers' => array(
        'factories' => array(
            'flashMessage' => function($sm) {
                $flashMessenger = $sm->getServiceLocator()
                    ->get('ControllerPluginManager')
                    ->get('flashmessenger');                                   
                 $message = new Application\View\Helper\FlashMessages();
                 $message->setFlashMessenger($flashMessenger);
                 return $message;
            },
            'auth'  =>  function($sm) {
                $service = $sm->getServiceLocator()->get('Auth_Service');
                $helper = new Application\View\Helper\Auth();
                $helper->setAuthService($service);
                return $helper;
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
    'constants' => array(
        'SALT'  =>  'sdsdkjaj'
    )
);
