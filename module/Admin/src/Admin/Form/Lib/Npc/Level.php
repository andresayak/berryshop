<?php

namespace Admin\Form\Lib\Npc;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class Level extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_npc_row, $_level_row;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'level',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'def',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'prison_rate',
            'type' => 'text',
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'level',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     => 'def',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     =>   'prison_rate',
            'required' =>   true
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setNpcRow($npcRow)
    {
        $this->_npc_row = $npcRow;
        if($this->_level_row === null){
            $this->get('level')->setValue($npcRow->getLevelRowset()->maxValue('level')+1);
        }
        return $this;
    }
    
    public function getNpcRow()
    {
        return $this->_npc_row;
    }
    
    public function setLevelRow($levelRow)
    {
        $this->_level_row = $levelRow;
        $this->get('level')->setAttribute('disabled', true);
        $this->getInputFilter()->remove('level');
        $this->setData($levelRow->toArray())
             ->setNpcRow($levelRow->getNpcRow());
        return $this;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Lib\Npc\Level\Table');
        if($this->_level_row === null){
            $this->_level_row = $table->createRow(array(
                'level'         =>  $this->get('level')->getValue(),
                'npc_code'      =>  $this->getNpcRow()->code,
                'def'           =>  $this->get('def')->getValue(),
                'prison_rate'   =>  $this->get('prison_rate')->getValue(),
            ));
            $table->add($this->_level_row);
        }else{
            $this->_level_row->setFromArray(array(
                'def'           =>  $this->get('def')->getValue(),
                'prison_rate'   =>  $this->get('prison_rate')->getValue(),
            ));
            $table->edit($this->_level_row);
        }
    }
}

