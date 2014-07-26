<?php

namespace Admin\Form\Lib\Quest;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class Reward extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_quest_row;
    
    public function __construct($sm, $questRow = null, $rewardRow = null)
    {
        $this->_sm = $sm;
        $this->_quest_row = $questRow;
        
        parent::__construct();
        $this->setAttribute('method', 'post');
        $optionData = array();
        foreach($this->getSm()->get('Lib\Object\Rowset')->getItems() AS $objectRow){
            if(($rewardRow!==null and $rewardRow->object_code == $objectRow->code)
                or (!$questRow->getRewardRowset()->getBy('object_code', $objectRow->code))
                and $objectRow->isCountType()
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
        
        $this->add(array(
            'name' => 'count',
            'type' => 'text',
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     => 'count',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'),
            ),
        ));
        $inputFilter->add(array(
            'name'     =>   'object_code',
            'required' =>   false
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function finish()
    {
        $table = $this->getSm()->get('Lib\Quest\Reward\Table');
        $rewardRow = $table->createRow(array(
            'quest_id'      =>  $this->_quest_row->id,
            'object_code'   =>  $this->get('object_code')->getValue(),
            'count'         =>  $this->get('count')->getValue(),
        ));
        $rewardRow->save();
    }
}

