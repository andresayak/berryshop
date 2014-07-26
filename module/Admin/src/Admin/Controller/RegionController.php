<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class RegionController extends AbstractController
{
    public function indexAction()
    {
        $rowset = $this->getTable('Region')->fetchAll();
        return array(
            'rowset' => $rowset
        );
    }
    
    public function addAction()
    {
        $form = new Form\Region($this->getServiceLocator());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Region')){
            return;
        }
        $form = new Form\Region($this->getServiceLocator());
        $form->setData($row->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $row->setFromArray($form->getData())
                    ->save();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Region')){
            return;
        }
        $row->delete();
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('Region')){
            return;
        }
        
        return array(
            'regionRow' =>  $row
        );
    }
    
    public function historyAction()
    {
        if(!$row = $this->getRow('Region', 'region_id')){
            return;
        }
        
        $table = $this->getTable('Region\History');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), array('region_id = ?' => $row->id), 'time_add DESC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        
        return array(
            'paginator' => $paginator,
            'regionRow' =>  $row
        );
    }
    
    public function battlesAction()
    {
        if(!$row = $this->getRow('Region', 'region_id')){
            exit;
        }
        
        $table = $this->getTable('Region\Battle');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), array('region_id = ?' => $row->id), 'time_add DESC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        
        return array(
            'paginator' => $paginator,
            'regionRow' =>  $row
        );
    }
    
    public function battleAction()
    {
        if(!$battleRow = $this->getRow('Region\Battle')){
            exit;
        }
        
        return array(
            'regionRow' =>  $battleRow->getRegionRow(),
            'battleRow' =>  $battleRow
        );
    }
    
    public function objectsAction()
    {
        if(!$row = $this->getRow('Region', 'region_id')){
            return;
        }
        
        $table = $this->getTable('Region\Object');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), array('region_id = ?' => $row->id), 'count DESC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(20);
        
        return array(
            'paginator' =>  $paginator,
            'regionRow' =>  $row
        );
    }
    
    public function npcsAction()
    {
        if(!$row = $this->getRow('Region', 'region_id')){
            return;
        }
        
        $table = $this->getTable('Region\Npc');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), array('region_id = ?' => $row->id), 'id ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        
        return array(
            'paginator' => $paginator,
            'regionRow' =>  $row
        );
    }
    
     public function villagesAction()
    {
        if(!$row = $this->getRow('Region', 'region_id')){
            return;
        }
        
        $table = $this->getTable('Region\Village');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), array('region_id = ?' => $row->id), 'id ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        
        return array(
            'paginator' => $paginator,
            'regionRow' =>  $row
        );
    }
}
