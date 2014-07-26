<?php

return array(
    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
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
);
