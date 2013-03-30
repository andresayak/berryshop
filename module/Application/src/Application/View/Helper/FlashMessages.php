<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FlashMessages extends AbstractHelper {

    protected $flashMessenger;
    protected $_UIClass;
    
    public function setFlashMessenger($flashMessenger) 
    {
        $this->flashMessenger = new \Zend\View\Helper\FlashMessenger();
        $this->flashMessenger->setPluginFlashMessenger($flashMessenger);
        return $this;
    }

    public function __invoke()
    {
        return $this;
    }
    
    public function __toString()
    {
        $namespaces = array('default',
            'error', 'success',
            'info', 'warning'
        );

        $messageString = '';

        foreach ($namespaces as $ns) {
            $this->flashMessenger->setNamespace($ns);
            $messages = array_merge(
                $this->flashMessenger->getMessages(), $this->flashMessenger->getCurrentMessages()
            );

            if (!$messages)
                continue;
            $UIClass =  $this->getUIClass();
            $class = (($UIClass)?$UIClass.' ':'').(($UIClass)?$UIClass.'-':'').$ns;
            $messageString .= '<div class="'.$class.'">' . implode('<br />', $messages). '</div>';
        }

        return $messageString;
    }
    
    public function setUIClass($class)
    {
        $this->_UIClass = $class;
        return $this;
    }
    
    public function getUIClass()
    {
        return $this->_UIClass;
    }
}