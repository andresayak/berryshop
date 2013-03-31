<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Application\Model\User\Table;

class Themes
{
    public function preDispatch(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $data = explode('\\',$controller);
        if(isset($data[2]) and $data[2] == 'Admin')
            $event->getViewModel()->setTemplate('layout/admin');
        
    }
}