<?php

namespace Admin\Form\Lib\Quest;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class Need extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_quest_row;
    
    public function __construct($sm, $questRow = null, $needRow = null)
    {
        $this->_sm = $sm;
        $this->_quest_row = $questRow;
        
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $optionData = array(
            null => ' - - '
        );
        foreach($this->getSm()->get('Lib\Object\Rowset')->getItems() AS $objectRow){
            if(($needRow!==null and $needRow->object_code == $objectRow->code)
                or (!$questRow->getNeedRowset()->getBy('object_code', $objectRow->code))
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
        
        $value_options = array(
            'alliance_join' =>  'Alliance join',
            'alliance_create'   =>  'Alliance create',
            'alliance_give'   =>  'Alliance give',
            'capital_battle_join'=> 'Attack capital',
            'capital_battle_win'=> 'Attack capital and win',
            'capital_guard'=> 'Capital guard',
            'alliance_moderator'=> 'Alliance moderator',
            'alliance_chat'=> 'Alliance chat',
            'buy' => 'Buy resource',
            'create_city' => 'Create city',
            'build' => 'Build',
            'build_resource'=>'Build resource',
            'build_noresource'=>'Build noresource',
            'skill' => 'Skill',
            'sell' => 'Sell resource',
            'recruit' => 'Recruit unit',
            'loot' => 'Loot',
            'transport_resource' => 'Transport resource',
            'transport_unit' => 'Transport units',
            'attack_npc' => 'Attack NPC',
            'attack_npc_win' => 'Attack NPC ad win',
            'attack_npc_win_renegades' => 'Attack NPC (renegades) and win',
            'attack_npc_win_bandits' => 'Attack NPC (bandits) and win',
            'attack_npc_win_spirits' => 'Attack NPC (spirits) and win',
            'attack_npc_win_caves' => 'Attack NPC (caves) and win',
            'attack' => 'Attack city',
            'attack_enemy' => 'Attack city enemy',
            'attack_win' => 'Attack city and win',
            'spy' => 'Spy',
            'shop_gem' => 'Shop gems',
            'shop_object' => 'Shop items',
            'shop_main' => 'Shop main',
            'spy_win' => 'Spy and win',
            'village' => 'Occupet village',
            'use_gem' => 'Use gems',
            'wish' => 'Wish',
            'top_up' => 'Top up'
        );
        asort($value_options);
        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'value_options' => $value_options
             )   
        ));
        
        $this->add(array(
            'name' => 'level',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'level',
            'required' => false,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     => 'count',
            'required' => false,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     =>   'object_code',
            'required' =>   false,
            'filters'  => array(
                array('name' => 'Null'),
            ),
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Lib\Quest\Need\Table');
        $needRow = $table->createRow(array(
            'quest_id'      =>  $this->_quest_row->id,
            'object_code'   =>  (($this->get('object_code')->getValue())?$this->get('object_code')->getValue():null),
            'count'         =>  $this->get('count')->getValue(),
            'level'         =>  $this->get('level')->getValue(),
            'type'          =>  $this->get('type')->getValue(),
        ));
        $needRow->save();
    }
}

