<?php

namespace Admin\Form\System;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ExportUnitCost extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_option_prefix = 'resource_';
    protected $_sep_line = "\n";
    protected $_sep_cell = "\t";
    protected $_head = array('translate_code', 'time_build');
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'data',
            'type' => 'text',
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name'     =>   'data',
            'required' =>   true
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    public function setSm($sm)
    {
        $this->_sm = $sm;
        return $this;
    }
    
    public function getDataForCsv() 
    {
        $libObjectRowset = $this->getSm()->get('Lib\Object\Rowset');
        
        $resource = array();
        $head = $this->_head;
        foreach ($libObjectRowset->getItems() AS $objectRow) {
            if ($objectRow->type == 'resource') {
                $head[] =  $this->_option_prefix.$objectRow->code;
                $resource[] = $objectRow->code;
            }
        }
        $data = array();
        foreach ($libObjectRowset->getItems() AS $objectRow) {
            if ($objectRow->type == 'unit') {
                foreach ($objectRow->getLevelRowset()->getItems() AS $levelRow) {
                    $resourceData = array();
                    foreach ($resource AS $resource_code) {
                        if ($needRow = $levelRow->getNeedRowset()->getBy('object_code', $resource_code))
                            $resourceData[] = $needRow->count;
                        else
                            $resourceData[] = '0';
                    }
                    $data[] = array_merge(array($objectRow->code, $levelRow->time_build), $resourceData);
                }
            }
        }
        return array_merge(array($head), $data);
    }
    
    public function saveDataFromCsv()
    {
        $libObjectRowset = $this->getSm()->get('Lib\Object\Rowset');
        $levelTable = $this->getSm()->get('Lib\Object\Level\Table');
        $levelNeedTable = $this->getSm()->get('Lib\Object\Level\Need\Table');
                
        $csvData = array();
        $dataString = explode($this->_sep_line, $this->get('data')->getValue());
        foreach ($dataString as $rowString) {
            $csvRow = explode($this->_sep_cell, trim($rowString));
            $csvData[] = $csvRow;
        }
        $resource = array();
        foreach ($csvData[0] AS $index => $csvRow)
            if (preg_match('/^' . $this->_option_prefix . '(.*)$/i', trim($csvRow), $match))
                $resource[$index] = $match[1];
        for ($i = 1; $i < count($csvData); $i++) {
            $count = count($this->_head) + count($resource);
            if(count($csvData[$i]) <= $count){
                $value = trim($csvData[$i][0]);
                if(!preg_match('/^\d*$/', $value, $match)){
                    $objectRow = $libObjectRowset->getBy('code', $value);
                    if($objectRow and isset($csvData[$i][1])){
                        $time_build = intval($csvData[$i][1]);
                        if (!$levelRow = $objectRow->getLevelRowset()->getBy('level', 1)) {
                            $levelRow = $levelTable->createRow(array(
                                'object_code'   => $objectRow->code,
                                'level'         => $value,
                                'time_build'    => $time_build
                            ));
                            $levelTable->add($levelRow);
                            $objectRow->getLevelRowset()->add($levelRow);
                        } else {
                            
                            if($levelRow->time_build != $time_build){
                                $levelRow->time_build = $time_build;
                                $levelRow->save();
                            }
                        }
                        foreach ($resource AS $index => $resource_code) {
                            $count = intval((isset($csvData[$i][$index]))?$csvData[$i][$index]:0);
                            $needRow = $levelRow->getNeedRowset()->getBy('object_code', $resource_code);
                            if (!$needRow) {
                                if($count){
                                    $needRow = $levelNeedTable->createRow(array(
                                        'level_id'      => $levelRow->id,
                                        'object_code'   => $resource_code,
                                        'count'         => $count
                                    ));
                                    $levelNeedTable->add($needRow);
                                    $levelRow->getNeedRowset()->add($needRow);
                                }
                            } else {
                                if ($count) {
                                    $needRow->count = $count;
                                    $needRow->save();
                                }else{
                                    $needRow->delete();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

