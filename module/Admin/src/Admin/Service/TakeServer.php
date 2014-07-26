<?php

namespace Admin\Service;

use Zend\Session\Container;

class TakeServer 
{
    protected $_server_row, $_session, $_sm;
    
    public function __construct($sm) 
    {
        $this->_sm = $sm;
        return $this;
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function getSession()
    {
        if(null === $this->_session){
            $this->_session =  new Container('TakeServer');
        }
        return $this->_session;
    }
    
    public function take($id)
    {
        $this->getSession()->id = $id;
        $this->_server_row = null;
        return $this;
    }
    
    public function getServerRow()
    {
        if($this->_server_row === null) {
            $rowset = $this->getSm()->get('Server\Rowset');

            if (!isset($this->getSession()->id) or !$this->getSession()->id) {
                $id = $rowset->minValue('id');
                $this->take($id);
            }
            $this->_server_row = $rowset->getBy('id', $this->getSession()->id);
        }
        return $this->_server_row;
    }
}