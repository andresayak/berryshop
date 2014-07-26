<?php

namespace Admin\Form\Lib\Object\Level;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class AttrForm extends Form
{
    protected $inputFilter;
    protected $_object_rowset, $_sm;
    public function __construct($sm, $levelRow, $attrValueRow = false)
    {
        parent::__construct();
        $this->_sm = $sm;
        $this->setAttribute('method', 'post');
        $optionData = array();
        foreach($sm->get('Lib\Attr\Rowset')->getItems() AS $attrRow){
            if((!$row = $levelRow->getAttrRowset()->getBy('attr_code', $attrRow->code) 
                    or ($attrValueRow and $attrValueRow->attr_code == $attrRow->code)
                )
                and (($levelRow->getObjectRow()->type=='unit' and $attrRow->assignation == 'object')
                    or $levelRow->getObjectRow()->type!='unit' or $levelRow->getObjectRow()->unit_recruit=='wall')
            )
                $optionData[$attrRow->code] = $attrRow->code;
        }
        $this->add(array(
            'name' => 'attr_code',
            'type' => 'select',
            'options' => array(
                'value_options' => $optionData
             )   
        ));
        
        $this->add(array(
            'name' => 'value',
            'type' => 'text',
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     =>   'value',
            'required' =>   true,
        ));
        
        $inputFilter->add(array(
            'name'     =>   'attr_code',
            'required' =>   true,
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
}

