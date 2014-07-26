<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class LevelUserForm extends Form
{
    protected $inputFilter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'level',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'translate_code',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'exp',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'translate_code',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'level',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'exp',
            'required' => true,
        ));
        $this->setInputFilter($inputFilter);
    }
}

