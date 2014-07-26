<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class LandscapeForm extends Form
{
    protected $inputFilter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'translate_code',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'icon_filename',
            'type' => 'text',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'build_status',
            'options' => array(
                'value_options' => array(
                    'off' => 'Off',
                    'on' => 'On',
                ),
            ),
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
                'name'     => 'translate_code',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
        ));
        $this->setInputFilter($inputFilter);
    }
}

