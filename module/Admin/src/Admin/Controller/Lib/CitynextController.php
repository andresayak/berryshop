<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;

class CitynextController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('Lib\Citynext');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway());
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\Citynext;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getTable('Lib\Citynext')->getTableGateway()->insert($form->getData());
                $this->addMessage('Saved');
                return $this->_redirect('index');
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$citynextRow = $this->getRow('Lib\Citynext')){
            return;
        }
        $form = new Form\Lib\Citynext;
        $form->setData($citynextRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $citynextRow->setFromArray($form->getData());
                $citynextRow->save();
                
                $this->addMessage('Saved');
                return $this->_redirect('index');
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$citynextRow = $this->getRow('Lib\Citynext')){
            return;
        }
        $citynextRow->delete();
        
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$citynextRow = $this->getRow('Lib\Citynext')){
            return;
        }
        return array(
            'citynextRow' =>  $citynextRow,
        );
    }
    
    public function objectAddAction()
    {
        if(!$citynextRow = $this->getRow('Lib\Citynext')){
            return;
        }
        $rowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Lib\Citynext\Object($citynextRow, $rowset);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('Lib\Citynext\Object');
                $objectRow = $table->createRow($form->getData());
                $objectRow->count_id = $citynextRow->count;
                $table->add($objectRow);
                $this->addMessage('Saved');
                return $this->_redirect('view', array('count' => $citynextRow->count));
            }
        }
        return array(
            'citynextRow'   =>  $citynextRow,
            'form'          =>  $form
        );
    }
    
    public function objectEditAction()
    {
        if(!$objectRow = $this->getRow('Lib\Citynext\Object')){
            return;
        }
        $rowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Lib\Citynext\Object($objectRow->getCountRow(), $rowset, $objectRow);
        $form->setData($objectRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $objectRow->setFromArray($form->getData());
                $objectRow->save();
                
                $this->addMessage('Saved');
                return $this->_redirect('view', array('count' => $objectRow->count_id));
            }
        }
        return array(
            'objectRow'     =>  $objectRow,
            'citynextRow'   =>  $objectRow->getCountRow(),
            'form'          =>  $form
        );
    }
    
    public function objectRemoveAction()
    {
        if(!$objectRow = $this->getRow('Lib\Citynext\Object')){
            return;
        }
        $objectRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('count' => $objectRow->count_id));
    }
}
