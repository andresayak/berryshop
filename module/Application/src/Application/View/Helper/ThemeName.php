<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ThemeName extends AbstractHelper
{
    protected $_config; 
    
    public function __construct($config) 
    {
        $this->_config = $config;
    }
    
    public function __invoke($name)
    {
        return $this->_config[$name];
    }
}