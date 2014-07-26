<?php

namespace Admin\Form\Lib\Npc;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class Guard extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_npc_row, $_guard_row;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setNpcRow($npcRow)
    {
        $this->_npc_row = $npcRow;
        
        $optionData = array();
        foreach($this->getSm()->get('Lib\Object\Rowset')->getUnitRowset()->getItems() AS $objectRow){
            if(!$row = $npcRow->getGuardRowset()->getBy('object_code', $objectRow->code) 
                or ($this->_guard_row and $this->_guard_row->id == $row->id)
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
        
        $this->getInputFilter()->add(array(
            'name'     => 'object_code',
            'required' => true,
        ));
        
        return $this;
    }
    
    public function getNpcRow()
    {
        return $this->_npc_row;
    }
    
    public function setGuardRow($guardRow)
    {
        $this->_guard_row = $guardRow;
        $this->setNpcRow($guardRow->getNpcRow())
             ->setData($guardRow->toArray());
        return $this;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Lib\Npc\Guard\Table');
        if($this->_guard_row === null){
            $this->_guard_row = $table->createRow(array(
                'object_code'   =>  $this->get('object_code')->getValue(),
                'npc_code'      =>  $this->getNpcRow()->code,
            ));
            $table->add($this->_guard_row);
        }else{
            $this->_guard_row->setFromArray(array(
                'object_code'   =>  $this->get('object_code')->getValue(),
            ));
            $table->edit($this->_guard_row);
        }
    }
}

