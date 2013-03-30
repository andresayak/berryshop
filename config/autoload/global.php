<?php

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=berryshop;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    
    'service_manager' => array(
        'factories' => array(
            'ZendDbAdapterAdapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'phpSettings'=>array(
        'display_startup_errors'    => false,
        'display_errors'            => false,
        'max_execution_time'        => 60,
        'mbstring.internal_encoding'=> 'UTF-8'
    )
);
