<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class FountainController extends AbstractController
{
    public function indexAction()
    {
        return array(
            'fountainRowset' => $this->getTable('Fountain')->fetchAll()
        );
    }
    
    public function addAction()
    {
        $form = new Form\Fountain($this->getServiceLocator());
                
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
        if(!$row = $this->getRow('Fountain')){
            return;
        }
        $form = new Form\Fountain($this->getServiceLocator());
        $form->setFountainRow($row);     
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
        if(!$row = $this->getRow('Fountain')){
            return;
        }
        return array(
            'fountainRow'   =>  $row
        );
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Fountain')){
            return;
        }
        $table = $this->getTable('Fountain');
        $table->delete($row);
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function addObjectAction()
    {
        if(!$fountainRow = $this->getRow('Fountain')){
            return;
        }
        $rowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Fountain\ObjectForm($fountainRow, $rowset);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('Fountain\Object');
                $objectRow = $table->createRow($form->getData());
                $objectRow->fountain_id = $fountainRow->id;
                $table->add($objectRow);
                
                $this->addMessage('Saved');
                return $this->_redirect('view', array('id'=>$fountainRow->id));
            }
        }
        return array(
            'fountainRow'   =>  $fountainRow,
            'form'      =>  $form
        );
    }
    
    public function editObjectAction()
    {
        if(!$objectRow = $this->getRow('Fountain\Object')){
            return;
        }
        $rowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Fountain\ObjectForm($objectRow->getFountainRow(), $rowset, $objectRow);
        $form->setData($objectRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $objectRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('id' => $objectRow->fountain_id));
            }
        }
        return array(
            'objectRow'     =>  $objectRow,
            'fountainRow'   =>  $objectRow->getFountainRow(),
            'form'          =>  $form
        );
    }
    
    public function removeObjectAction()
    {
        if(!$objectRow = $this->getRow('Fountain\Object')){
            return;
        }
        $objectRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('id' => $objectRow->fountain_id));
    }
}
