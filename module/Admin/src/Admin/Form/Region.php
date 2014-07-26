<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class Region extends Form
{
    protected $inputFilter;
    protected $_server_rowset;
    protected $_sm;
    
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
            'name' => 'size',
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
            'name' => 'ratio_npc',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'ratio_village',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'ratio_landscape',
            'type' => 'text',
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'status',
            'options' => array(
                'value_options' => array(
                    'on'    =>  'On',
                    'off'   =>  'Off'
                ),
            ),
        ));
        
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'translate_code',
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
        
        $inputFilter->add(array(
            'name' => 'status',
            'required' => true,
        ));
        
        $inputFilter->add(array(
            'name' => 'size',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
                array(
                    'name' => 'Between', 
                    'options' => array('min' => 10, 'max' => 1000)
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'ratio_landscape',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
                array(
                    'name' => 'Between', 
                    'options' => array('min' => 1, 'max' => 90)
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'ratio_npc',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
                array(
                    'name' => 'Between', 
                    'options' => array('min' => 1, 'max' => 90)
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'ratio_village',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
                array(
                    'name' => 'Between', 
                    'options' => array('min' => 1, 'max' => 90)
                )
            )
        ));
        
        $this->setInputFilter($inputFilter);
        
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function getServerOptions()
    {
        $data = array();
        foreach($this->getSm()->get('Server\Rowset')->getItems() AS $row){
            $data[$row->id] = $row->name;
        }
        return $data;
    }
    
    public function finish()
    {
        $regionRow = $this->getSm()->get('Region\Table')->createRow($this->getData());
        $regionRow->setFromArray(array(
            'capital_geo_x' =>  rand(3, $regionRow->size-1),
            'capital_geo_y' =>  rand(3, $regionRow->size-1)
        ));
        $regionRow->save();
        $service = $this->getSm()->get('RegionMapCreator\Service');
        $service->setRatio(5)
            ->setRegionRow($regionRow);
        $service->generateLandscape();
    }
}

