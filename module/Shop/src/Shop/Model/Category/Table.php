<?php

namespace Shop\Model\Category;

use Ap\Model\Table AS Prototype;

class Table extends Prototype
{
    protected $_cols = array('id', 'parent_id', 'title', 'status');
    protected $_name = 'category';
    
    public function fetchAllEnabled()
    {
        return $this->getTableGateway()->select(array(
            'status'    =>  'on'
        ));
    }
}