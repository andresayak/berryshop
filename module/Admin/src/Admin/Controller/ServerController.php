<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;

class ServerController extends AbstractController
{
    public function indexAction()
    {
        $rowset = $this->getServiceLocator()->get('Server\Rowset');
        return array(
            'rowset' => $rowset
        );
    }
    
    public function addAction()
    {
        $form = new Form\Server;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getTable('Server')->createRow($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Server')){
            return;
        }
        $form = new Form\Server;
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
    
    public function viewAction()
    {
        if(!$row = $this->getRow('Server')){
            return;
        }
        
        $service = $this->getServiceLocator()->get('TakeServer\Service');
        $service->take($row->id);
        return array(
            'serverRow' =>  $row
        );
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Server')){
            return;
        }
        $row->delete();
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
}
