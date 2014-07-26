<?php

namespace Ap\Model;

use Game\Model\ObjectCountInterface;
use Zend\Db\Sql\Expression;

class Row
{
    protected $_data;
    protected $_table;
    protected $_sm;
    protected $_force_insert = false;
    protected $_modifiedFields = array();
    protected $_block_for_update = false;
    static $rowsets = false;
    
    public function setForceInsert($mode) 
    {
         $this->_force_insert = $mode;
         return $this;
    }
    
    public function __sleep()
    {
        if($this->_data === null){
            throw new \Exception('empty row['.  get_class($this).']');
        }
        $data = array('_data');
        foreach($this->getTable()->getSleeps() AS $col){
            if(preg_match('/^\_(.*)\_rowset$/', $col, $match)){
                $method = 'get'.ucfirst($match[1]).'Rowset';
                if(method_exists($this, $method)){
                    $this->{$method}();
                    $data[] = $col;
                }
            }
            if(preg_match('/^\_(.*)\_row$/', $col, $match)){
                $method = 'get'.ucfirst($match[1]).'Row';
                if(method_exists($this, $method)){
                    $this->{$method}();
                    $data[] = $col;
                }
            }
        }
        return $data;
    }
    
    public function __wakeup()
    {
        
    }
        
    public function getSm()
    {
        if($this->_sm === null){
            throw new \Exception('Service manager not set');
        }
        return $this->_sm;
    }
    
    public function setSm($sm)
    {
        $this->_sm = $sm;
        $vars = get_object_vars($this);
        foreach($vars AS $value){
            if($value instanceof Rowset){
                $value->setSm($sm);
            }
        }
        return $this;
    }
    
    public function isModified($col)
    {
        return array_key_exists($col, $this->_modifiedFields);
    }
    
    public function exchangeArray($data)
    {
        $this->_data = array();
        foreach($this->getTable()->getCols() AS $col){
            $this->_data[$col] = null;
        }
        foreach($data AS $col=>$value){
            $this->_data[$col] = $value;
        }
        return $this;
    }
    
    public function setFromArray($data)
    {
        foreach($data AS $col=>$value){
            if(in_array($col, $this->getTable()->getCols())){
                $this->{$col} = $value;
            }
        }
        return $this;
    }
    
    public function __get($col)
    {
        if(isset($this->_data[$col]))
            return $this->_data[$col];
    }

    public function __set($col, $value)
    {
        if (!in_array($col, $this->getTable()->getCols())) {
            throw new \Exception("Specified column \"$col\" is not in the row [".get_class($this)."]");
        }
        
        if($this->_data[$col] !== $value){
            if(in_array($col, $this->getTable()->getCols()) and !$this->isModified($col)){
                $this->_modifiedFields[$col] = $this->_data[$col];
            }
            $this->_data[$col] = $value;
        }
    }
    
    public function __isset($columnName)
    {
        return array_key_exists($columnName, $this->_data);
    }
    
    public function toArrayForSave()
    {
        if ($this->{$this->getTable()->getKey()} === null or $this->_force_insert) {
            $data = $this->toArray();
        }else{
            $data = array();
            foreach($this->toArray() AS $col=>$value){
                if($this->isModified($col)){
                    if(in_array($col, $this->getTable()->getCounters())){
                        $diff = $value - $this->_modifiedFields[$col];
                        if($diff){
                            $data[$col] = new Expression($col.(($diff)?'+'.$diff:$diff));
                        }
                    }else{
                        $data[$col] = $value;
                    }
                }
            }
        }
        foreach($data AS $col=>$value){
            if(!in_array($col, $this->getTable()->getCols())){
                unset($data[$col]);
            }
        }
        return $data;
    }
    
    public function toArray()
    {
        return $this->_data;
    }
    
    public function getArrayCopy()
    {
        $data = get_object_vars($this);
        return $data['_data'];
    }
    
    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }
    
    public function getTable()
    {
        if($this->_table === null) {
            $path = explode('\\', get_class($this));
            array_pop($path);
            $baseClass = implode('\\',$path);
            $rowClass = preg_replace('/^[^\\\]*\\\Model\\\/', '', $baseClass.'\Table');
            $this->_table = $this->getSm()->get($rowClass);
        }
        return $this->_table;
    }
    
    public function getDefaults()
    {
        return $this->getTable()->getDefaults() + array_fill_keys($this->getTable()->getCols(), null);
    }
    
    public function save()
    {
        if($this instanceof ObjectCountInterface){
            if($this->count < 0)
                throw new \Exception('count < 0, ['.$this->count.'] in class'.  get_class($this));
        }
        $save = false;
        if ($this->{$this->getTable()->getKey()} === null or $this->_force_insert) {
            $this->_preInsert();
            $save = true;
        }else{
            $this->_preUpdate();
            if(count($this->_modifiedFields)){
                $save = true;
            }
        }
        if($save){
            $this->getTable()->saveRow($this, $this->_force_insert);
            $this->_modifiedFields = array();
            $this->_force_insert = false;
        }
        return $this;
    }
    
    public function delete()
    {
        $this->getTable()->deleteRow($this);
        return $this;
    }
    
    protected function _preInsert()
    {
        
    }
    
    protected function _preUpdate()
    {
        
    }
    
    public function blockForUpdate()
    {
        if($this->_block_for_update){
            return $this;
            //throw new \Exception('row is blocked');
        }
        if(!$this->{$this->getTable()->getKey()}){
            throw new \Exception('Invalid primary key('.$this->getTable()->getKey().')');
        }
        $row = $this->getTable()->fetchByPKForUpdate($this->{$this->getTable()->getKey()});
        if(!$row){
            throw new \Exception('Blocked row not found');
        }
        $this->exchangeArray($row->toArray());
        $this->_block_for_update = true;
        return $this;
    }
}