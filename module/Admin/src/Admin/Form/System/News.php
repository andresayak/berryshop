<?php

namespace Admin\Form\System;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class News extends Form
{
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
        ));
        $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
        ));
        $this->add(array(
            'name' => 'anonce',
            'type' => 'textarea',
        ));
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setSm($sm)
    {
        $this->_sm = $sm;
        return $this;
    }
    
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter) {
        parent::setInputFilter($inputFilter);
        
        $this->setData($inputFilter->getValues());
        $this->setMessages($inputFilter->getMessages());
    }
}

