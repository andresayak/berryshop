<?php

namespace Admin\Form\Lib\Citynext;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Object extends Form
{
    protected $inputFilter;
    
    protected $_object_rowset;
    protected $_types = array(
        'build' =>  array('level'),
        'resource' =>  array('count'),
        'skill' =>  array('level'),
        'unit' =>  array('count'),
    );
    public function __construct($cityRow, $objectRowset, $objectRow = false)
    {
        $this->_object_rowset = $objectRowset;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $optionData = array();
        foreach($this->getObjectRowset()->getItems() AS $row){
            if(
                (!$cityRow->getObjectRowset()->getBy('object_code', $row->code)
                or ($objectRow and $objectRow->object_code == $row->code))
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
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'count',
            'required' => false,
            'filters' => array(
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

