<?php

namespace Admin\Form;

use Zend\Form\Form;

class Shop extends Form
{
    protected $inputFilter, $_sm, $_shop_row;
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
            'name' => 'title',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'price',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options'   =>  array(
                'value_options' =>  array(
                    'main'      =>  'Main', 
                    'progress'  =>  'Progress',
                    'military'  =>  'Military', 
                    'gem'       =>  'Gem'
                )
            )
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
            'name'  =>  'title',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
        )));
        
        $filter->add(array(
            'name'  =>  'translate_code',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
        )));
        
        $filter->add(array(
            'name'  =>  'type',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        $filter->add(array(
            'name'  =>  'price',
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
    
    public function setShopRow($row)
    {
        $this->_shop_row = $row;
        $this->setData($row->toArray());
        return $this;
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Shop\Table');
        if($this->_shop_row === null){
            $this->_shop_row = $table->createRow($this->getData());
            $table->add($this->_shop_row);
        }else{
            $this->_shop_row->setFromArray($this->getData());
            $this->_shop_row->save();
        }
    }
}

