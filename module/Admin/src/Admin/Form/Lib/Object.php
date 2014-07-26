<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Object extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_types = array('item', 'unit', 'build', 'resource', 'skill', 'bless');
    protected $_type;
    public function __construct($type)
    {
        $this->_type = $type;
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
            'name' => 'icon_filename',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'default_level',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'default_count',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'formula',
            'type' => 'textarea',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'unit_type',
            'options' => array(
                'value_options' => array(
                    'cavalry'   =>  'Cavalry',
                    'magic'     =>  'Magic',
                    'infantry'  =>  'Infantry',
                    'artillery' =>  'Artillery',
                    'siege'     =>  'Siege'
                ),
            ),
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'unit_recruit',
            'options' => array(
                'value_options' => array(
                    null        =>  ' - -',
                    'barrack'   =>  'Barrack',
                    'magic'     =>  'Magic',
                    'balor'     =>  'Balor',
                    'wall'      =>  'Wall',
                ),
            ),
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'usable',
            'options' => array(
                'value_options' => array(
                    'off'   =>  'off',
                    'on'    =>  'on',
                ),
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'buy_in_market',
            'options' => array(
                'value_options' => array(
                    'off'   =>  'off',
                    'on'    =>  'on',
                ),
            ),
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'usable_in_transport',
            'options' => array(
                'value_options' => array(
                    'off'   =>  'off',
                    'on'    =>  'on',
                ),
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'usable_in_spy',
            'options' => array(
                'value_options' => array(
                    'off'   =>  'off',
                    'on'    =>  'on',
                ),
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'usable_in_attack',
            'options' => array(
                'value_options' => array(
                    'off'   =>  'off',
                    'on'    =>  'on',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'unit_city',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    null    =>  ' - -',
                    1       =>  1,
                    2       =>  2,
                    3       =>  3,
                    4       =>  4,
                    5       =>  5,
                ),
            ),
        ));
        
        
        $this->add(array(
            'name' => 'position',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'time_build',
            'type' => 'text',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'resource_type',
            'options' => array(
                'value_options' => array(
                    null            =>  ' - -',
                    'base'          =>  'Base',
                    'game'          =>  'Game',
                    'production'    =>  'Production',
                    'magic'         =>  'Magic',
                ),
            ),
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     =>   'code',
            'required' =>   true,
            'filters'  =>   array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
        ));
        
        $inputFilter->add(array(
            'name'     =>   'title',
            'required' =>   true,
        ));
        
        $inputFilter->add(array(
            'name'     =>   'usable',
            'required' =>   false,
        ));
        $inputFilter->add(array(
            'name'     =>   'usable_in_transport',
            'required' =>   false,
        ));
        $inputFilter->add(array(
            'name'     =>   'usable_in_attack',
            'required' =>   false,
        ));
        $inputFilter->add(array(
            'name'     =>   'usable_in_spy',
            'required' =>   false,
        ));
        
        $inputFilter->add(array(
            'name'      =>  'buy_in_market',
            'required'  =>  false,
        ));
        
        $inputFilter->add(array(
            'name'      =>  'time_build',
            'required'  =>  false,
            'filters'   =>  array(
                array('name' => 'Digits'),
            ),
        ));
        
        $inputFilter->add(array(
            'name'      =>  'formula',
            'required'  =>  false,
            'filters'   =>  array(
                array('name' => 'Null'),
            ),
        ));
        
        $inputFilter->add(array(
            'name'      =>  'default_level',
            'required'  =>  false,
            'filters'   =>  array(
                array('name' => 'Digits'),
                array('name' => 'Null')
            )
        ));
        $inputFilter->add(array(
            'name'      =>  'default_count',
            'required'  =>  false,
            'filters'   =>  array(
                array('name' => 'Digits'),
                array('name' => 'Null')
            )
        ));
        
        $inputFilter->add(array(
            'name'      =>  'resource_type',
            'required'  =>  false
        ));
        
        $inputFilter->add(array(
            'name'      =>  'unit_recruit',
            'required'  =>  false
        ));
        
        $inputFilter->add(array(
            'name'      =>  'unit_type',
            'required'  =>  false
        ));
        
        $inputFilter->add(array(
            'name'      =>   'unit_city',
            'required'  =>   false
        ));
        
        $inputFilter->add(array(
            'name'      =>   'icon_filename',
            'required'  =>   false
        ));
        
        $inputFilter->add(array(
            'name'     =>   'position',
            'required' =>   false
        ));
        $this->setInputFilter($inputFilter);
    }
}

