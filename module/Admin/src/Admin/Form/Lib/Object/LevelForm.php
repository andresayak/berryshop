<?php

namespace Admin\Form\Lib\Object;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class LevelForm extends Form
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
            'name' => 'time_build',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'preview_filename',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'exp',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'level',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     => 'time_build',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     =>   'preview_filename',
            'required' =>   false
        ));
        
        $inputFilter->add(array(
            'name'     =>   'exp',
            'required' =>   false
        ));
        $this->setInputFilter($inputFilter);
    }
}

