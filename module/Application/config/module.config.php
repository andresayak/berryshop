<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/auth/login',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'index',
                    ),
                ),
            ),
            'signup' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/auth/signup',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'signup',
                    ),
                ),
            ),
            'forgot' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/auth/forgot',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'forgot',
                    ),
                ),
            ),
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
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
            }
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'  => 'Application\Controller\IndexController',
            'Application\Controller\Auth'   => 'Application\Controller\AuthController'
        ),
    ),
     'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Auth'       => function($sm){
                $service = new Application\Service\Auth($sm->get('User_Table'));
                return $service;
             },
             'User_Table' =>  function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $table = new Application\Model\User\Table($dbAdapter);
                return $table;
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
        ),
    ),
    'constants' => array(
        'SALT'  =>  'sdsdkjaj'
    )
);
