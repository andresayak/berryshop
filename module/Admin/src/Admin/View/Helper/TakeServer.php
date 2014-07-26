<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class TakeServer extends AbstractHelper
{
    protected $_service;
    
    public function __construct(\Admin\Service\TakeServer $takeService) 
    {
        $this->_service = $takeService;
        return $this;
    }
    
    public function __invoke()
    {
        return $this->_service->getServerRow();
    }
}