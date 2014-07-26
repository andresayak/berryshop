<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;

class LevelUserController extends AbstractController
{
    public function indexAction()
    {
        return array(
            'levelRowset' => $this->getServiceLocator()->get('Lib\Level\User\Rowset')
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\LevelUserForm;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getTable('Lib\Level\User')->getTableGateway()->insert($form->getData());
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Lib\Level\User')){
            return $this->_redirect();
        }
        $form = new Form\Lib\LevelUserForm;
        $form->setData($row->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $row->setFromArray($form->getData())
                    ->save();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Lib\Level\User')){
            return;
        }
        $row->delete();
        $this->addMessage('Deleted', 'success');
        return $this->_redirect();
    }
}
