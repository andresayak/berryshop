<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\ModuleManager\ModuleManager,
    Zend\Mvc\MvcEvent,
    Zend\Module\Consumer\AutoloaderProvider,
    Zend\EventManager\StaticEventManager;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $config = ($e->getApplication()->getConfig());
        if(isset($config['constants']))
            foreach($config['constants'] as $name => $value)
                if(!defined($name))
                    define($name, $value);
        $e->getTarget()->getEventManager()->attach('dispatch', array($this, 'auth'), 100);
        $e->getTarget()->getEventManager()->attach('dispatch', array($this, 'themes'), 100);
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
    
    public function auth(MvcEvent $event)
    {
        $auth = $event->getTarget()->getServiceManager()->get('Auth_Service');
        return $auth->preDispatch($event);
    }
    
    public function themes(MvcEvent $event)
    {
        $themes = $event->getTarget()->getServiceManager()->get('Themes_Service');
        return $themes->preDispatch($event);
    }
    
}
