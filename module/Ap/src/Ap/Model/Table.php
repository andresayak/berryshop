<?php

namespace Ap\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;

class Table 
{
    protected $_name;
    protected $_rowClass;
    protected $_rowsetClass;
    protected $_tableGateway;   
    
    public function __construct(Adapter $dbAdapter)
    {
        $this->_tableGateway = new TableGateway($this->_name, $dbAdapter, null, $this->_resultSetPrototype());
    }
    
    protected function _resultSetPrototype()
    {
        $path = explode('\\', get_class($this));
        array_pop($path);
        $baseClass = implode('\\',$path);
        if($this->_rowsetClass){
            $rowsetClass =  $baseClass.'\\'.$this->_rowsetClass;
            if(!class_exists($rowsetClass))
                throw new \Exception('rowsetClass not found['.$rowsetClass.']');
            $resultSetPrototype = new $rowsetClass;
        }else $resultSetPrototype = new ResultSet;
        
        if($this->_rowClass){
            $rowClass =  $baseClass.'\\'.$this->_rowClass;
            if(!class_exists($rowClass))
                throw new \Exception('rowClass not found['.$rowClass.']');
            $rowPrototype = new $rowClass;
        }else $rowPrototype = new Row;
        return $resultSetPrototype->setArrayObjectPrototype($rowPrototype);
    }
    
    public function getTableGateway()
    {
        return $this->_tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->getTableGateway()->select();
        return $resultSet;
    }

    public function getRowById($id)
    {
        $id  = (int) $id;
        $rowset = $this->getTableGateway()->select(array('id' => $id));
        return $rowset->current();
    }

    public function saveRow(Row $row)
    {
        $data = $row->toArray();
        if (!isset($row->id) or !$id = $row->id) {
            $this->getTableGateway()->insert($data);
        } else {
            if ($this->getRowById($id))
                $this->getTableGateway()->update($data, array('id' => $id));
            else
                throw new \Exception('Form id does not exist');
        }
    }

    public function deleteRow(Row $row)
    {
        $this->getTableGateway()->delete(array('id' => $row->id));
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getMetaData()
    {
        $metadata = new Metadata($this->getTableGateway()->getAdapter());
        try{
            $data =  @$metadata->getTable($this->getName());
        }catch(Exception $e){
            
        }
        return $data;
    }
}