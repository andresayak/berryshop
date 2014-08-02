<?php

return array(
    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
    ),
    'themes'    =>  array(
        'frontend'  =>  'default',
        'backend'   =>  'default'
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
        'save_handler'  =>  'Cache\Memcached'
    ),
    'constants' =>  array(
        'SALT'          =>  's0dsjk2kjq1',
        'ERROR_MAILTO'  =>  'andresayak@gmail.com'
    ),
    'mailer'    =>  array(
        'status'    =>  true,
        'site_email'  =>  'robot@soft-berry.com.ua',
        'site_name' =>  'shop.soft-berry.com.ua',
    ),
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=berryshop;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'memcached' =>  array(
        'servers' => array('host' => '127.0.0.1')
    ),
    'redis' =>  array(
        'host'      =>  '127.0.0.1',
        'port'      =>  '6379',
        'prefix'    =>  'berryshop'
    ),
    'service_manager' => array(
        'factories' => array(
            'ZendDbAdapterAdapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'phpSettings'=>array(
        'memory_limit'                  =>  '256M',
        'display_startup_errors'        =>  0,
        'display_errors'                =>  0,
        'date.timezone'                 =>  'UTC',
        'max_execution_time'            =>  180,
        'mbstring.internal_encoding'    =>  'UTF-8'
    ),
    'acl'   =>  array(
        'roles' => array(
            array(
                'code'      =>  'guest',
                'parent'    =>  null,
                'priority'  =>  0
            ),
            array(
                'code'      =>  'user',
                'parent'    =>  'guest',
                'priority'  =>  10
            ),
            array(
                'code'      =>  'admin',
                'parent'    =>  'user',
                'priority'  =>  20
            ),
         ),
        'rules' =>  array(
            array(
                'role'          =>  'guest',
                'resource'      =>  'default',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'guest',
                'resource'      =>  'login',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'user',
                'resource'      =>  'logout',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'guest',
                'resource'      =>  'news',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'guest',
                'resource'      =>  'faq',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'user',
                'resource'      =>  'else',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'user',
                'resource'      =>  'home',
                'permission'    =>  'allow'
            ),
            array(
                'role'          =>  'guest',
                'resource'      =>  'home',
                'permission'    =>  'deny'
            ),
        )
    )
);
