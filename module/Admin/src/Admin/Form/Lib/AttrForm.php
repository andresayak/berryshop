<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class AttrForm extends Form
{
    protected $inputFilter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'code',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'list',
            'type' => 'textarea',
        ));
        
        $this->add(array(
            'name' => 'assignation',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    'object' => 'for Object',
                    'city' => 'for City',
                    'user' => 'for User',
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'max_value',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'min_value',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'default_value',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'icon_filename',
            'type' => 'text',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'datatype',
            'options' => array(
                'value_options' => array(
                    'int' => 'int',
                    'bool' => 'bool',
                    'float' => 'float',
                    'select' => 'list',
                ),
            ),
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'code',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
        ));

        $floatFilters = array(
            array('name' => 'StringTrim'),
            array('name' => 'PregReplace', 'options' => array('pattern'=>'/,/', 'replacement'=>'.')),
            array('name' => 'Null', 'options' => array('type'=>'string'))
        );
        
        $inputFilter->add(array(
            'name'     => 'title',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'max_value',
            'required' => false,
            'filters'  => $floatFilters,
            'validators'=>array(
            )
        ));
        $inputFilter->add(array(
            'name'     => 'min_value',
            'required' => false,
            'filters'  => $floatFilters,
            'validators'=>array(
            )
        ));
        $inputFilter->add(array(
            'name'     => 'default_value',
            'required' => false,
            'filters'  => $floatFilters,
            'validators'=>array(
            )
        ));
        
        $inputFilter->add(array(
            'name'     => 'list',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
            'validators'    =>  array(
                array(
                    'name' => 'Regex',
                    'options'   =>  array(
                        'pattern'   =>  '/^((\d+)\:([^\:]+)\;?)+$/i'
                    ),
                    'break_chain_on_failure' => true
                ),
            )
        ));
        $this->setInputFilter($inputFilter);
    }
}

