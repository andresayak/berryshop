<?php

namespace Application\Model\User;

use Ap\Model\Table AS Prototype;

class Table extends Prototype
{
    protected $_name = 'user';
    protected $_cols = array('id', 'email', 'role', 'password');
    
    public function getRowByEmail($email)
    {
        $rowset = $this->getTableGateway()->select(array('email' => $email));
        return $rowset->current();
    }
}