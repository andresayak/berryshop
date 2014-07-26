<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class SystemExport extends AbstractHelper
{
    protected $_data;
    public function __invoke($data)
    {
        $this->_data = $data;
        return $this;
    }
    
    public function getTable()
    {
        $max = count($this->_data[0]);
        $head = array_shift($this->_data);
        $data = $this->_data;
        foreach($data As $row)
            $max = max($max, count($row));
        
        $str = '<table class="table  table-bordered" width="100%">';
        $str.= '<thead><tr><th>'.implode("</th><th>", $head).'</th></tr></thead>';
        $str.= '<tbody>';
        foreach($data As $row){
            $count = count($row);
            $num = $max - $count;
            if($num)
                $row = array_merge($row, array_fill($count, $num, ''));
            $str.="<tr><td>".implode("</td><td>", $row)."</td></tr>";
        }
        $str.= '</tbody>';
        $str.='</table>';
        return $str;
    }
    
    public function getCsv($sep = "\t")
    {
        $max = count($this->_data[0]);
        $head = array_shift($this->_data);
        $data = $this->_data;
        foreach($data As $row)
            $max = max($max, count($row));
        
        $str = implode($sep, $head)."\n";
        foreach($data As $row){
            $count = count($row);
            $num = $max - $count;
            if($num)
                $row = array_merge($row, array_fill($count, $num, ''));
            $str.=implode($sep, $row)."\n";
        }
        return $str;
    }
}