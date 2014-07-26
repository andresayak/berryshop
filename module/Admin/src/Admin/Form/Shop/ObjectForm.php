<?php

namespace Admin\Form\Shop;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ObjectForm extends Form
{
    protected $inputFilter;
    
    protected $_object_rowset;
    public function __construct($shopRow, $objectRowset, $objectRow = false)
    {
        $this->_object_rowset = $objectRowset;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $optionData = array();
        foreach($this->getObjectRowset()->getItems() AS $row){
            if($row->isCountType() 
                and (!$shopRow->getObjectRowset()->getBy('object_code', $row->code)
                or ($objectRow and $objectRow->object_code == $row->code))
            )
                $optionData[$row->code] = $row->code.' ['.$row->type.']';
        }
        $this->add(array(
            'name' => 'object_code',
            'type' => 'select',
            'options' => array(
                'value_options' => $optionData
             )   
        ));
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'count',
            'required' => true,
            'filters' => array(
                array('name' => 'Digits'),
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

