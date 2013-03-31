<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Application\Model\User\Table;

class Auth
{
    protected $_user_row, $_auth_service;
    protected $_table;
    protected $_acl = null;
    
    public function __construct($table)
    {
        $this->_table = $table;
        return $this;
    }
    
    public function setAcl($acl)
    {
        $this->_acl = $acl;
        return $this;
    }
    
    public function getAcl()
    {
        return $this->_acl;
    }
    
    public function preDispatch(MvcEvent $event)
    {
        $acl = $this->getAcl();
        $role = $acl::DEFAULT_ROLE;
        if ($this->getUserRow()) {
            $role = $this->getUserRow()->role;
        }
        
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action     = $routeMatch->getParam('action');
        if (!$acl->hasResource($controller)) {
            throw new \Exception('Resource ' . $controller . ' not defined');
        }
 
        if (!$acl->isAllowed($role, $controller, $action)) {
            $router = $event->getRouter();
            $url    = $router->assemble(array(), array('name' => 'login'));
            $response = $event->getResponse();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', $url);
            $event->stopPropagation();  
        }
    }

    
    public function getUserRow()
    {
        if($this->_user_row === null) {
            $service = new AuthenticationService;
            if($id = $service->getIdentity()){
                $this->_user_row = $this->_table->getRowById($this->getAuthService()->getIdentity());
            }else $this->_user_row = false;
        }
        return $this->_user_row;
    }
    
    public function setAuthService(AuthenticationService $auth_service)
    {
        $this->_auth_service = $auth_service;
    }
    
    public function getAuthService()
    {
        if($this->_auth_service === null){
            $this->_auth_service = new AuthenticationService();
        }
        return $this->_auth_service;
    }
    
    public function isAuthenticate($email, $password)
    {
        $dbAdapter = $this->_table->getTableGateway()->getAdapter();
        $authAdapter = new AuthAdapter($dbAdapter,
            $this->_table->getName(), 'email', 'password', 'MD5(concat("'.SALT.'", ?))');
        $authAdapter
            ->setIdentity($email)
            ->setCredential($password);
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
            $authService = new AuthenticationService();
            $authService->setAdapter($authAdapter);
            $storage = $authService->getStorage();
            $storage->write($authAdapter->getResultRowObject()->id);
            return true;
        }
        return false;
    }
    
    public function logout()
    {
        //$this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
    }
}