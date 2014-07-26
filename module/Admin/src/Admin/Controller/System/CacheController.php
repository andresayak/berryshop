<?php

namespace Admin\Controller\System;

use Ap\Controller\AbstractController;
use Zend\Paginator;

class CacheController extends AbstractController
{
    public function indexAction()
    {
        $type = $this->params()->fromQuery('type', false);
        if($type){
            $status = true;
            foreach($this->getServiceLocator()->get('Server\Rowset')->getItems() AS $serverRow){
                $host = $serverRow->host;
                $ch = curl_init('http://'.$host.'/api/main.cacheUpdate?key='.$serverRow->api_key.'&name='.$type);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $out = curl_exec($ch);
                curl_close($ch);
                try {
                    $data = \Zend\Json\Json::decode($out);
                    if($data->status!= true){
                        $this->addMessage($host.' ('.$data->error.')', 'error');
                    }
                } catch (\Zend\Json\Exception\RuntimeException $exc) {
                    $this->addMessage($host.' ('.$exc->getMessage().')', 'error');
                    $status = false;
                }
            }
            if($status)
                $this->addMessage('Reset', 'success');
            return $this->_redirect('index');
        }
    }
}