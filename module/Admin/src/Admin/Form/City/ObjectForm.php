<?php

namespace Admin\Form\City;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class ObjectForm extends Form
{
    protected $inputFilter;
    
    protected $_object_rowset;
    protected $_types = array(
        'build' =>  array('level'),
        'resource' =>  array('count'),
        'skill' =>  array('level'),
        'unit' =>  array('count'),
        'bless' =>  array()
    );
    public function __construct($cityRow, $objectRowset, $type, $objectRow = false)
    {
        if(!isset($this->_types)){
            throw new \Exception;
        }
        $this->_object_rowset = $objectRowset;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $optionData = array();
        foreach($this->getObjectRowset()->getItems() AS $row){
            if($row->type == $type 
                and (
                    !$cityRow->getObjectRowset()->getBy('object_code', $row->code)
                    or ($objectRow and $objectRow->object_code == $row->code)
                )
            )
                $optionData[$row->code] = $row->getTranslateCode();
        }
        $this->add(array(
            'name' => 'object_code',
            'type' => 'select',
            'options' => array(
                'value_options' => $optionData
             )   
        ));
        
        if(in_array('level', $this->_types[$type])){
            $this->add(array(
                'name' => 'level',
                'type' => 'text',
            ));
        }
        if(in_array('count', $this->_types[$type])){
            $this->add(array(
                'name' => 'count',
                'type' => 'text',
            ));
        }
        
        $inputFilter = new InputFilter();
        if(in_array('count', $this->_types[$type])){
            $inputFilter->add(array(
                'name'     => 'count',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Digits'),
                    array('name' => 'Null')
                ),
            ));
        }
        if(in_array('level', $this->_types[$type])){
            $inputFilter->add(array(
                'name'     => 'level',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Digits'),
                    array('name' => 'Null')
                ),
            ));
        }
        $inputFilter->add(array(
            'name'     =>   'object_code',
            'required' =>   true
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getObjectRowset()
    {
        if(null === $this->_object_rowset){
            throw new \Exception('objectRowset not set');
        }
        return $this->_object_rowset;
    }
}

