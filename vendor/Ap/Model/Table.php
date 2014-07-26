<?php

namespace Ap\Model;

use Zend\Db\TableGateway\TableGateway;
use Ap\Model\Rowset;
use Zend\Cache\Storage\StorageInterface;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

class Table 
{
    protected $_name;
    protected $_sm;
    protected $_key = 'id';
    protected $_cols = array();
    protected $_sleeps = array(
    );
    protected $_defaults = array();
    protected $_rowClass = 'Row';
    protected $_counters = array();
    protected $_rowsetClass = 'Rowset';
    protected $_tableGateway;   
    protected $_cache_status = false;
    
    public function getCounters()
    {
        return $this->_counters;
    }
    
    public function getCols()
    {
        return $this->_cols;
    }
    
    public function getSleeps()
    {
        return $this->_sleeps;
    }
    
    public function getDefaults()
    {
        return $this->_defaults;
    }
    
    public function __construct($sm, $db)
    {
        $this->setSm($sm);
        $this->_tableGateway = new TableGateway($this->_name, $db, null, $this->_resultSetPrototype());
    }
    
    protected function _resultSetPrototype()
    {
        $path = explode('\\', get_class($this));
        array_pop($path);
        $baseClass = implode('\\',$path);
        $rowsetClass =  $baseClass.'\\'.$this->_rowsetClass;
        if(!class_exists($rowsetClass))
            $rowsetClass = __NAMESPACE__.'\\'.$this->_rowsetClass;
        $resultSetPrototype = new $rowsetClass;
        $rowClass =  $baseClass.'\\'.$this->_rowClass;
        if(!class_exists($rowClass))
            $rowClass = __NAMESPACE__.'\\'.$this->_rowClass;
        $rowPrototype = new $rowClass;
        $rowPrototype->setTable($this);
        $rowPrototype->setSm($this->getSm());
        return $resultSetPrototype->setSm($this->getSm())->setTable($this)->setArrayObjectPrototype($rowPrototype);
    }
    
    public function getTableGateway()
    {
        return $this->_tableGateway;
    }

    public function cached($function, $suffix)
    {
        $cacheStatus = false;
        if($this->_cache_status){
            $service = $this->getSm()->get('CacheManager');
            if($service->getStatus() and $cache = $service->isCacheByTable($this)){
                $cacheStatus = true;
                $key = get_class($this).md5($suffix);
                $result = $cache->getItem($key);
                if($result !== null){
                    if($result instanceof Rowset){
                        $result->setSm($this->getSm());
                        $result->setArrayObjectPrototype($this->_resultSetPrototype()->getArrayObjectPrototype());
                    }elseif($result instanceof Row){
                        $result->setSm($this->getSm());
                    }
                    $result->setTable($this);
                    return $result;
                }
            }
        }
        $this->getTableGateway()->initialize();
        $result = $function();
        
        if($cacheStatus){
            $cache->setItem($key, $result);
        }
        return $result;
    }
    
    public function fetchAll()
    {
        $tableGataway = $this->getTableGateway();
        return $this->cached(function() use($tableGataway){
            return $tableGataway->select();
        }, '->fetchAll()');
    }
    
    public function fetchBy($col, $value)
    {
        $this->getTableGateway()->initialize();
        $tableGataway = $this->getTableGateway();
        return $this->cached(function() use($col, $value, $tableGataway){
            if(is_array($value)){
                $select = $this->getTableGateway()->getSql()->select();
                $select->where(function (Where $where) use ($col, $value){
                    $where->in($col, $value);
                });
                return  $this->getTableGateway()->selectWith($select);
            }else
                return $this->getTableGateway()->select(array($col => $value))->current();
        }, '->fetchBy('.print_r($col, true).', '.print_r($value, true).')');
    }
    
    public function fetchAllBy($col, $value)
    {
        $this->getTableGateway()->initialize();
        $tableGataway = $this->getTableGateway();
        return $this->cached(function() use($col, $value, $tableGataway){
            return $this->getTableGateway()->select(array($col => $value));
        }, '->fetchAllBy('.$col.', '.$value.')');
        
    }
    
    public function fetchByPK($value)
    {
        return $this->fetchBy($this->getKey(), $value);
    }
    
    public function fetchByPKForUpdate($id)
    {
        $adapter = $this->getTableGateway()->getAdapter();
        
        $qi = function($name) use ($adapter) { return $adapter->platform->quoteIdentifier($name); };
        $fp = function($name) use ($adapter) { return $adapter->driver->formatParameterName($name); };
        
        
        $sql = "SELECT * FROM ".$qi($this->getName())
            ." WHERE ".$qi($this->getKey())." = ".$fp('id')
            ." FOR UPDATE";
        
        $statement = $adapter->query($sql);
        $result = $statement->execute(array('id' => $id));
        
        $resultSet = clone $this->_resultSetPrototype();
        $resultSet->initialize($result);
        
        return $resultSet->current();
    }
    
    public function fetchByArray(array $arg, $row = false)
    {
        $rowSet = $this->getTableGateway()->select($arg);
        return (!$row) ? $rowSet:$rowSet->current();
    }
    
    public function saveRow(Row $row, $force_insert = false)
    {
        $data = $row->toArrayForSave();
        if ($row->{$this->getKey()} === null or $force_insert) {
            $this->getTableGateway()->insert($data);
            if($value = ($this->getTableGateway()->getLastInsertValue())){
                $row->{$this->getKey()} = $value;
            }
        } else {
            if(count($data))
                $this->getTableGateway()->update($data, array($this->getKey() => $row->{$this->getKey()}));
        }
    }

    public function deleteRow(Row $row)
    {
        $this->getTableGateway()->delete(array($this->getKey() => $row->{$this->getKey()}));
    }
    
    public function createRow($data = array(), $save = false)
    {
        $resultSet = $this->getTableGateway()->getResultSetPrototype();
        $newRow = clone $resultSet->getArrayObjectPrototype();
        
        $newRow->setForceInsert(true)->exchangeArray($data + $this->getDefaults());
        if($save)
            $this->saveRow($newRow);
        return $newRow;
    }

    public function getName()
    {
        return $this->_name;
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
    
    public function getKey()
    {
        return $this->_key;
    }
}
