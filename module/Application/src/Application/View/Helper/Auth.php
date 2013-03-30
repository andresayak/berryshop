<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Auth extends AbstractHelper
{
    protected $_authService;
    
    public function setAuthService($authService)
    {
        $this->_authService = $authService;
        return $this;
    }
    
    public function __invoke()
    {
        return $this->_authService->getUserRow();
    }
}