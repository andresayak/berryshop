<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class LevelAllianceForm extends Form
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
            'name' => 'gold',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'gem',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'member_limit',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'moderator_limit',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'level',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'gold',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'gem',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'member_limit',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'moderator_limit',
            'required' => true,
        ));
        $this->setInputFilter($inputFilter);
    }
}

