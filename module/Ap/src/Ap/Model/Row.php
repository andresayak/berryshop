<?php

namespace Ap\Model;

class Row
{
    protected $_data;
    protected $_cols;
    
    public function exchangeArray($data)
    {
        $this->_data = array();
        foreach($this->_cols AS $col)
            if(isset($data[$col]))
                $this->_data[$col] = $data[$col];
        return $this;
    }
    
    public function __get($columnName)
    {
        if(isset($this->_data[$columnName]))
            return $this->_data[$columnName];
        throw new \Exception("Specified column \"$columnName\" is not in the row");
    }

    public function __set($columnName, $value)
    {
        if (!array_key_exists($columnName, $this->_data)) {
            throw new \Exception("Specified column \"$columnName\" is not in the row");
        }
        $this->_data[$columnName] = $value;
        $this->_modifiedFields[$columnName] = true;
    }
    
    public function __isset($columnName)
    {
        return array_key_exists($columnName, $this->_data);
    }
    public function toArray()
    {
        return $this->_data;
    }
}