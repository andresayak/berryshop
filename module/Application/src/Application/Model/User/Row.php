<?php

namespace Application\Model\User;

use Ap\Model\Row AS Prototype;
class Row extends Prototype
{
    protected $_password_original;
    
    public function setPassword($password)
    {
        $this->_data['password'] = md5(SALT.$password);
        $this->_password_original = $password;
        return $this;
    }
    
    public function getPasswordOriginal()
    {
        return $this->_password_original;
    }
}