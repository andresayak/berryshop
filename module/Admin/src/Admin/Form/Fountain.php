<?php

namespace Admin\Form;

use Zend\Form\Form;

class Fountain extends Form
{
    protected $_sm, $_fountain_row;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'translate_code',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'rate',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'position',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'icon_filename',
            'type' => 'text',
        ));
        
        $filter = new \Zend\InputFilter\InputFilter();
        
        $filter->add(array(
            'name'  =>  'translate_code',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
        )));
        
        $filter->add(array(
            'name'  =>  'rate',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
        )));
        
        $filter->add(array(
            'name'  =>  'position',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
        )));
        
        $filter->add(array(
            'name'  =>  'icon_filename',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
        )));
        $this->setInputFilter($filter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setFountainRow($row)
    {
        $this->_fountain_row = $row;
        $this->setData($row->toArray());
        return $this;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Fountain\Table');
        if($this->_fountain_row === null){
            $this->_fountain_row = $table->createRow($this->getData());
            $table->add($this->_fountain_row);
        }else{
            $this->_fountain_row->setFromArray($this->getData());
            $this->_fountain_row->save();
        }
    }
}