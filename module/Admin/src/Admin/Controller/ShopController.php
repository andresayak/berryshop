<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class ShopController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('Shop');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'position ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $form = new Form\Shop($this->getServiceLocator());
                
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        
        return array(
            'form'  =>  $form
        );
    }
    
    public function editAction()
    {
        if(!$shopRow = $this->getRow('Shop')){
            return;
        }
        $form = new Form\Shop($this->getServiceLocator());
        $form->setShopRow($shopRow);     
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        
        return array(
            'form'  =>  $form
        );
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('Shop')){
            return;
        }
        return array(
            'shopRow'   =>  $row
        );
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Shop')){
            return;
        }
        $table = $this->getTable('Shop');
        $table->delete($row);
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function addObjectAction()
    {
        if(!$shopRow = $this->getRow('Shop')){
            return;
        }
        $rowset = $this->getServiceLocator()->get('Lib/Object/Rowset');
        $form = new Form\Shop\ObjectForm($shopRow, $rowset);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('Shop/Object');
                $objectRow = $table->createRow($form->getData());
                $objectRow->shop_id = $shopRow->id;
                $table->add($objectRow);
                
                $this->addMessage('Saved');
                return $this->_redirect('view', array('id'=>$shopRow->id));
            }
        }
        return array(
            'shopRow'   =>  $shopRow,
            'form'      =>  $form
        );
    }
    
    public function editObjectAction()
    {
        if(!$objectRow = $this->getRow('Shop\Object')){
            return;
        }
        $rowset = $this->getServiceLocator()->get('Lib/Object/Rowset');
        $form = new Form\Shop\ObjectForm($objectRow->getShopRow(), $rowset, $objectRow);
        $form->setData($objectRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $objectRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('id' => $objectRow->shop_id));
            }
        }
        return array(
            'objectRow' =>  $objectRow,
            'shopRow'   =>  $objectRow->getShopRow(),
            'form'      =>  $form
        );
    }
    
    public function removeObjectAction()
    {
        if(!$objectRow = $this->getRow('Shop\Object')){
            return;
        }
        $objectRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('id' => $objectRow->shop_id));
    }
}
