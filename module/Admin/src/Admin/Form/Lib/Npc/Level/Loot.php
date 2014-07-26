<?php

namespace Admin\Form\Lib\Npc\Level;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter; 
use Game\Model\Lib\Npc\Level\Loot\Row AS LootRow;
use Game\Model\Lib\Npc\Level\Row AS LevelRow;

class Loot extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_level_row, $_loot_row;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'probability',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'diff',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'count',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'),
                array('name' => 'Null')
            ),
        ));
        
        $inputFilter->add(array(
            'name'     => 'diff',
            'required' => false,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        
        $inputFilter->add(array(
            'name'     => 'probability',
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
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setLevelRow(LevelRow $levelRow)
    {
        $this->_level_row = $levelRow;
        
        $optionData = array();
        foreach($this->getSm()->get('Lib\Object\Rowset')->getItems() AS $objectRow){
            if($objectRow->isCountType() 
                and (!$row = $levelRow->getLootRowset()->getBy('object_code', $objectRow->code) 
                or ($this->_loot_row and $this->_loot_row->id == $row->id))
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
    
    public function getLevelRow()
    {
        if($this->_level_row === null){
            throw new \Exception('level_row not set');
        }
        return $this->_level_row;
    }
    
    public function setLootRow(LootRow $lootRow)
    {
        $this->_loot_row = $lootRow;
        $this->setLevelRow($lootRow->getLevelRow())
             ->setData($lootRow->toArray());
        return $this;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Lib\Npc\Level\Loot\Table');
        if($this->_loot_row === null){
            $this->_loot_row = $table->createRow(array(
                'object_code'   =>  $this->get('object_code')->getValue(),
                'probability'   =>  $this->get('probability')->getValue(),
                'count'         =>  $this->get('count')->getValue(),
                'diff'          =>  (int)$this->get('diff')->getValue(),
                'level_id'      =>  $this->getLevelRow()->id,
            ));
            $table->add($this->_loot_row);
        }else{
            $this->_loot_row->setFromArray(array(
                'object_code'   =>  $this->get('object_code')->getValue(),
                'probability'   =>  $this->get('probability')->getValue(),
                'count'         =>  $this->get('count')->getValue(),
                'diff'          =>  (int)$this->get('diff')->getValue(),
            ));
            $table->edit($this->_loot_row);
        }
    }
}

