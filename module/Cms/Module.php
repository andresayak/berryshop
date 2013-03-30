<?php

namespace Cms;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Cms_Page_Table' =>  function($sm) {
                    $dbAdapter = $sm->get('ZendDbAdapterAdapter');
                    $table = new Model\Page\Table($dbAdapter);
                    return $table;
                },
            ),
        );
    }
}
