<?php

namespace Admin\Form;

use Zend\Form\Form;

class Alliance extends Form
{
    protected $inputFilter;
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'city_id',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
        ));
        
        $this->add(array(
            'name' => 'declaration',
            'type' => 'textarea',
        ));
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        parent::setInputFilter($inputFilter);
        
        $this->setData($inputFilter->getValues());
        $this->setMessages($inputFilter->getMessages());
    }
}

