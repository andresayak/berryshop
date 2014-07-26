<?php

namespace Admin\Form\Lib;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Game\Model\Lib\Quest\Row AS QuestRow;

class Quest extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_quest_row;
    
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
            'name' => 'exp',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'position',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    'multi'     =>  'Multiple',
                    'alliance'  =>  'Alliance',
                    'single'    =>  'Single',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    '0'     =>  'off',
                    '1'    =>  'on',
                ),
            ),
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'translate_code',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'position',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'exp',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'type',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name'     => 'status',
            'required' => true,
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function finish()
    {
        if($this->_quest_row === null){
            $questTable = $this->getSm()->get('Lib\Quest\Table');
            $this->_quest_row = $questTable->createRow();
        }
        $this->_quest_row->setFromArray(array(
            'position'          =>  $this->get('position')->getValue(),
            'translate_code'    =>  $this->get('translate_code')->getValue(),
            'type'              =>  $this->get('type')->getValue(),
            'exp'               =>  $this->get('exp')->getValue(),
            'status'            =>  $this->get('status')->getValue(),
        ));
        $this->_quest_row->save();
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setQuestRow(QuestRow $questRow)
    {
        $this->_quest_row = $questRow;
        $data = $questRow->toArray();
        $this->setData($data);
        return $this;
    }
    
}

