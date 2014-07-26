<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class City extends Form
{
    protected $inputFilter;
    protected $_sm;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'user_id',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'geo_x',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'geo_y',
            'type' => 'text',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'region_id',
            'options' => array(
                'value_options' => $this->getRegionOptions(),
            ),
        ));
        
        $userTable = $this->getSm()->get('User\Table');
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));
        $inputFilter->add(array(
            'name'     => 'geo_x',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators'=>array(
                array('name' => 'Digits'),
            )
        ));
        $inputFilter->add(array(
            'name' => 'geo_y',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
            )
        ));
        $validator = new \Zend\Validator\Db\RecordExists(
            array(
                'adapter'   =>  $userTable->getTableGateway()->getAdapter(),
                'table'     =>  $userTable->getName(),
                'field'     =>  'id'
            )
        );
        $inputFilter->add(array(
            'name' => 'user_id',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
                $validator
            )
        ));
        
        $this->setInputFilter($inputFilter);
        
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function getRegionOptions()
    {
        $data = array();
        foreach($this->getSm()->get('Region\Rowset')->getItems() AS $row){
            $data[$row->id] = $row->getTranslateCode();
        }
        return $data;
    }
}

