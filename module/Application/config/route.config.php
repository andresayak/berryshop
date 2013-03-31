<?php

return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action' => 'index',
            ),
        ),
    ),
    'admin' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/admin',
            'defaults' => array(
                'controller' => 'Application\Controller\Admin',
                'action' => 'index',
            ),
        ),
    ),
    'admin_users' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/admin/users[/:action]',
            'defaults' => array(
                'controller' => 'Application\Controller\Admin\Users',
                'action' => 'index',
            ),
        ),
    ),
    'login' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/auth/login',
            'defaults' => array(
                'controller' => 'Application\Controller\Auth',
                'action' => 'index',
            ),
        ),
    ),
    'logout' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/auth/logout',
            'defaults' => array(
                'controller' => 'Application\Controller\Auth',
                'action' => 'logout',
            ),
        ),
    ),
    'signup' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/auth/signup',
            'defaults' => array(
                'controller' => 'Application\Controller\Auth',
                'action' => 'signup',
            ),
        ),
    ),
    'forgot' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/auth/forgot',
            'defaults' => array(
                'controller' => 'Application\Controller\Auth',
                'action' => 'forgot',
            ),
        ),
    ),
    'application' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/application',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Index',
                'action' => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'default' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                    ),
                ),
            ),
        ),
    ),
);