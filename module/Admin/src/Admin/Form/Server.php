<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Server extends Form
{
    protected $inputFilter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'host',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'status',
            'options' => array(
                'value_options' => array(
                    'disabled' => 'Disabled',
                    'enabled' => 'Enabled',
                ),
            ),
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
                'name'     => 'host',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringToLower'),
                    array('name' => 'StringTrim'),
                ),
        ));
        $inputFilter->add(array(
                'name'     => 'name',
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

