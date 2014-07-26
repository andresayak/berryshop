<?php

namespace Admin\Form\Lib\Quest;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Depend extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_quest_row;
    
    public function __construct($sm, $questRow = null, $dependRow = null)
    {
        $this->_sm = $sm;
        $this->_quest_row = $questRow;
        
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $optionData = array();
        foreach($this->getSm()->get('Lib\Quest\Rowset')->getItems() AS $row){
            if((($dependRow!==null and $dependRow->quest_id == $row->id)
                or (!$questRow->getDependRowset()->getBy('depend_id', $row->id) ))
                and ($questRow->id != $row->id)
            )
                $optionData[$row->id] = $row->getTranslateCode();
        }
        $this->add(array(
            'name' => 'depend_id',
            'type' => 'select',
            'options' => array(
                'value_options' => $optionData
             )   
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     =>   'depend_id',
            'required' =>   true
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Lib\Quest\Depend\Table');
        $dependRow = $table->createRow(array(
            'quest_id'  =>  $this->_quest_row->id,
            'depend_id' =>  $this->get('depend_id')->getValue(),
        ));
        $dependRow->save();
    }
}

