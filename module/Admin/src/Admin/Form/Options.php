<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class Options extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'money_object',
            'type' => 'select',
        ));
        
        $this->add(array(
            'name' => 'donate_object',
            'type' => 'select',
        ));
        
    }
    
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        parent::setInputFilter($inputFilter);
        
        $options = array();
        foreach($inputFilter->getSm()->get('Lib\Object\Rowset')->getItems() AS $objectRow){
            if($objectRow->type == 'resource')
                $options[$objectRow->code] = $objectRow->getTranslateCode();
        }
        
        $this->get('money_object')->setValueOptions($options);
        $this->get('donate_object')->setValueOptions($options);
        $this->setData($inputFilter->getValues());
        $this->setMessages($inputFilter->getMessages());
    }
}

