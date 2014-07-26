<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Citynext extends Form
{
    protected $inputFilter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'count',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
        ));
        $this->setInputFilter($inputFilter);
    }
}

