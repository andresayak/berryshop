<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Npc extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_npc_row;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'code',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     =>   'code',
            'required' =>   true,
            'filters'  =>   array(
                array('name' => 'StripTags'),
                array('name' => 'StringToLower'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 4
                    ),
                    'break_chain_on_failure'=>true
                ),
                new \Zend\Validator\Db\NoRecordExists(
                    array(
                        'adapter'   =>  $this->getTable()->getTableGateway()->getAdapter(),
                        'table'     =>  $this->getTable()->getName(),
                        'field'     =>  'code',
                    )
                )
            )
        ));
        
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function getTable()
    {
        return $this->getSm()->get('Lib\Npc\Table');
    }
    
    function finish()
    {
        $table = $this->getTable();
        if($this->_npc_row === null){
            $this->_npc_row = $table->createRow(array(
                'code' => $this->get('code')->getValue()
            ));
            $table->add($this->_npc_row);
        }else{
            $this->_npc_row->setFromArray($this->getData());
            $table->edit($this->_npc_row);
        }
    }
    
    public function setNpcRow($npcRow)
    {
        $this->_npc_row = $npcRow;
        $this->get('code')->setAttribute('disabled', true);
        $this->getInputFilter()->remove('code');
        $this->setData($this->_npc_row->toArray());
        return $this;
    }
    
    public function getNpcRow()
    {
        return $this->_npc_row;
    }
}

