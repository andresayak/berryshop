<?php

namespace Admin\Form\System;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class ExportUnitAttr extends Form
{
    protected $inputFilter;
    protected $_sm;
    protected $_option_prefix = 'attr_';
    protected $_sep_line = "\n";
    protected $_sep_cell = "\t";
    protected $_head = array('translate_code');
    
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
        $libAttrRowset = $this->getSm()->get('Lib\Attr\Rowset');
        $libObjectRowset = $this->getSm()->get('Lib\Object\Rowset');

        $head = $this->_head;
        $attr = array();
        foreach ($libAttrRowset->getItems() AS $attrRow) {
            if($attrRow->assignation == 'object'){
                $head[] = $this->_option_prefix . $attrRow->code;
                $attr[] = $attrRow->code;
            }
        }

        $data = array();
        foreach ($libObjectRowset->getItems() AS $objectRow) {
            if ($objectRow->type == 'unit') {
                foreach ($objectRow->getLevelRowset()->getItems() AS $levelRow) {
                    $attrData = array();
                    foreach ($attr AS $attr_code) {
                        if ($attrRow = $levelRow->getAttrRowset()->getBy('attr_code', $attr_code)) {
                            $attrData[] = $attrRow->getAttrRow()->filterValue($attrRow->value);
                        } else
                            $attrData[] = '0';
                    }
                    $data[] = array_merge(array('title' => $objectRow->code), $attrData);
                }
            }
        }
        return array_merge(array($head), $data);
    }

    public function saveDataFromCsv()
    {
        $libObjectRowset = $this->getSm()->get('Lib\Object\Rowset');
        $levelAttrTable = $this->getSm()->get('Lib\Object\Level\Attr\Table');

        $csvData = array();
        $dataString = $this->get('data')->getValue();
        foreach (explode($this->_sep_line, $dataString) as $rowString) {
            $csvRow = explode($this->_sep_cell, trim($rowString));
            $csvData[] = $csvRow;
        }
        $attr = array();
        foreach ($csvData[0] AS $index => $csvRow)
            if (preg_match('/^' . $this->_option_prefix . '(.*)$/i', trim($csvRow), $match))
                $attr[$index] = $match[1];
        for ($i = 1; $i < count($csvData); $i++) {
            $count = count($this->_head) + count($attr);
            if(count($csvData[$i]) <= $count){
                $value = trim($csvData[$i][0]);
                if (!preg_match('/^(\d*)$/', $value, $match)) {
                    $objectRow = $libObjectRowset->getBy('code', $value);
                    if(!$objectRow){
                        echo 'object not found';exit;
                    }
                        if (!$levelRow = $objectRow->getLevelRowset()->getBy('level', 1))
                            throw new \Exception('Level not found[1]');

                        foreach ($attr AS $index => $attr_code) {
                            $attrvalue = floatval($csvData[$i][$index]);
                            if (!$attrRow = $levelRow->getAttrRowset()->getBy('attr_code', $attr_code) and $attrvalue) {
                                $attrRow = $levelAttrTable->createRow(array(
                                    'level_id' => $levelRow->id,
                                    'attr_code' => $attr_code,
                                    'value' => $attrvalue
                                ));
                                $attrRow->save();
                                $levelRow->getAttrRowset()->add($attrRow);
                            } else {
                                if ($attrRow)
                                    if ($attrvalue) {
                                        $attrRow->value = $attrvalue;
                                        $attrRow->save();
                                    }
                                    else
                                        $attrRow->delete();
                            }
                        }
                }
            }
        }
        return $this;
    }
}

