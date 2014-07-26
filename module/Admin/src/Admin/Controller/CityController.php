<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;

class CityController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('City');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway());
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function updateAction()
    {
        if(!$cityRow = $this->getRow('City')){
            return $this->_redirect();
        }
        $serverRow = $this->getServiceLocator()->get('TakeServer\Service')->getServerRow();
        $host = $serverRow->host;
        $ch = curl_init('http://'.$host.'/api/main.cityUpdate?key='.$serverRow->api_key.'&id='.$cityRow->id);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $out = curl_exec($ch);
        curl_close($ch);
        $status = true;
        try {
            $data = \Zend\Json\Json::decode($out);
            if ($data->status != true) {
                $this->addMessage($host . ' (' . $data->error . ')', 'error');
                $status = false;
            }
        } catch (\Zend\Json\Exception\RuntimeException $exc) {echo $out;exit;
            $this->addMessage($host . ' (' . $exc->getMessage() . ')', 'error');
            $status = false;
        }
        if($status)
            $this->addMessage('Update', 'success');
        return $this->redirect()->toRoute('admin', array(
            'controller'=>  'city',
            'action'    =>  'view'
        ), array(
            'query' =>  array('id'=>$cityRow->id)
        ));
    }
    
    public function addAction()
    {
        $form = new Form\City($this->getServiceLocator());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('City');
                $row = $table->createRow($form->getData());
                $table->add($row);
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('City')){
            return;
        }
        $form = new Form\City($this->getServiceLocator());
        $form->setData($row->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('City');
                $row->setFromArray($form->getData());
                $table->edit($row);
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('City')){
            return;
        }
        $table = $this->getTable('City');
        $table->delete($row);
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('City')){
            return;
        }
        return array(
            'cityRow' =>  $row,
        );
    }
    
    public function addObjectAction()
    {
        if(!$cityRow = $this->getRow('City')){
            return;
        }
        $type = $this->params()->fromQuery('type');
        $rowset = $this->getServiceLocator()->get('Lib/Object/Rowset');
        $form = new Form\City\ObjectForm($cityRow, $rowset, $type);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('City/Object');
                $objectRow = $table->createRow($form->getData());
                $objectRow->city_id = $cityRow->id;
                $table->add($objectRow);
                
                $this->addMessage('Saved');
                return $this->_redirect('view', array('id'=>$cityRow->id));
            }
        }
        return array(
            'type'      =>  $type,
            'cityRow'   =>  $cityRow,
            'form'      =>  $form
        );
    }
    
    public function editObjectAction()
    {
        if(!$objectRow = $this->getRow('City/Object')){
            return;
        }
        $type = $objectRow->getObjectRow()->type;
        $rowset = $this->getServiceLocator()->get('Lib/Object/Rowset');
        $form = new Form\City\ObjectForm($objectRow->getCityRow(), $rowset, $type, $objectRow);
        $form->setData($objectRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('City/Object');
                $objectRow->setFromArray($form->getData());
                $table->edit($objectRow);
                
                $this->addMessage('Saved');
                return $this->_redirect('view', array('id'=>$objectRow->city_id));
            }
        }
        return array(
            'type'      =>  $type,
            'cityRow'   =>  $objectRow->getCityRow(),
            'form'      =>  $form
        );
    }
    
    public function removeObjectAction()
    {
        if(!$objectRow = $this->getRow('City/Object')){
            return;
        }
        $objectRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('id' => $objectRow->city_id));
    }
}
