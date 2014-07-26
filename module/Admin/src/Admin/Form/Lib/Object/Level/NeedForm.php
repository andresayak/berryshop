<?php

namespace Admin\Form\Lib\Object\Level;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class NeedForm extends Form
{
    protected $inputFilter;
    protected $_object_rowset;
    public function __construct($objectRowset, $levelRow, $needRow = false)
    {
        $this->_object_rowset = $objectRowset;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $optionData = array();
        foreach($this->getObjectRowset()->getItems() AS $objectRow){
            if(!$row = $levelRow->getNeedRowset()->getBy('object_code', $objectRow->code) 
                or ($needRow and $needRow->id == $row->id)
            )
                $optionData[$objectRow->code] = $objectRow->code.' ['.$objectRow->type.']';
        }
        $this->add(array(
            'name' => 'object_code',
            'type' => 'select',
            'options' => array(
                'value_options' => $optionData
             )   
        ));
        
        $this->add(array(
            'name' => 'level',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'count',
            'required' => false,
            'filters'  => array(
                array('name' => 'Digits'),
                array('name' => 'Null')
            ),
        ));
        $inputFilter->add(array(
            'name'     => 'level',
            'required' => false,
            'filters'  => array(
                array('name' => 'Digits'),
                array('name' => 'Null')
            ),
        ));
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

