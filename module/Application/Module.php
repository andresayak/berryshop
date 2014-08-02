<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        $phpSettings = $config['phpSettings'];
        if($phpSettings) {
            foreach($phpSettings as $key => $value) {
                ini_set($key, $value);
            }
        }
        if(isset($config['constants'])){
            foreach($config['constants'] as $name => $value){
                if(!defined($name)){
                    define($name, $value);
                }
            }
        }
        
        $eventManager = $e->getApplication()->getEventManager();
        
        $eventManager->attach('dispatch', array($this, 'initThemes'));
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
        
        $eventManager->attach('dispatch.error', function($event){
            $exception = $event->getResult()->exception;
            if ($exception) {
                $sm = $event->getApplication()->getServiceManager();
                $service = $sm->get('Application\Service\ErrorHandling');
                $service->logException($exception);
            }
        });
    }
    
    public function initThemes($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $config = $sm->get('config');
        $theme_frontend = $config['themes']['frontend'];
        $theme_backend = $config['themes']['backend'];
        $replace_search = array('{theme_frontend}','{theme_backend}');
        $maps = $sm->get('Zend\View\Resolver\TemplateMapResolver')->getMap();
        $paths = array();
        foreach($sm->get('Zend\View\Resolver\TemplatePathStack')->getPaths()->toArray() AS $index=>$path){
            $paths[] = str_replace($replace_search, array('default', 'default'), $path);
            $paths[] = str_replace($replace_search, array($theme_frontend, $theme_backend), $path);
        }
        foreach($maps AS $index=>$map){
            $maps[$index] = str_replace($replace_search, array($theme_frontend, $theme_backend), $map);
        }
        $sm->get('Zend\View\Resolver\TemplateMapResolver')->setMap($maps);
        $sm->get('Zend\View\Resolver\TemplatePathStack')->setPaths($paths);
    }
    
    public function bootstrapSession($e)
    {
        if($_SERVER['PHP_SELF'] != '/usr/bin/phpunit'){
            $session = $e->getApplication()
                ->getServiceManager()
                ->get('Zend\Session\SessionManager');
            if(!$e->getRequest() instanceof \Zend\Console\Request 
                and $token = $e->getRequest()->getQuery('access_token', false)
            ){
                $session->setId($token);
            }
            $session->start();

            $container = new Container('initialized');
            if (!isset($container->init)) {
                 $session->regenerateId(true);
                 $container->init = 1;
            }
        }
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
    
    public function checkAcl(MvcEvent $event)
    {
        if($event->getRouteMatch() instanceof \Zend\Mvc\Router\Http\RouteMatch){
            $aclService = $event->getTarget()->getServiceManager()->get('Acl\Service');

            $role = 'guest';
            $authService = $event->getTarget()->getServiceManager()->get('Auth\Service');
            if($authService->getUserRow()){
                $role = $authService->getUserRow()->role;
            }
            $routeMatchName = $event->getRouteMatch()->getMatchedRouteName();
            if (!$aclService->isAllowed($role, $routeMatchName)) {
                $request = $event->getRequest();
                $response = $event->getResponse();
                if($request->isXmlHttpRequest()){
                    $event->setViewModel(new JsonModel(array(array('error'=>'access denied'))));
                    $response->setStatusCode(401);
                }else{
                    $response->setStatusCode(302);
                    $router = $event->getRouter();
                    $url    = $router->assemble(array(), array('name' => 'login'));
                    $response->getHeaders()->addHeaderLine('Location', $url);
                }
                $event->stopPropagation();  
            }
        }
    }
    
}
