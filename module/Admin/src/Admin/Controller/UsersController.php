<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;

class UsersController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('User');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway());
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $table = $this->getTable('User');
        $form = new Form\UserForm($table);
        $form->setData(array(
            'ban_status'    =>  'off'
        ));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
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
        if(!$row = $this->getRow('User')){
            return;
        }
        $table = $this->getTable('User');
        $form = new Form\UserForm($table, $row);
        $form->setData($row->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('User');
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
        if(!$row = $this->getRow('User')){
            return;
        }
        $authService = $this->getServiceLocator()->get('Auth\Service');
        if($authService->getAuthService()->getIdentity()==$row->id){
            $this->addMessage('You can not remove yourself', 'error');
            return $this->_redirect();
        }
        $table = $this->getTable('User');
        $table->delete($row);
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('User')){
            return;
        }
        return array(
            'userRow'       =>  $row,
            'questRowset'   =>  $this->getTable('Quest')->fetchAllViewByUserId($row->id)
        );
    }
    
    public function loginAction()
    {
        if(!$row = $this->getRow('User')){
            return;
        }
        $this->getServiceLocator()->get('Auth\Service')->authenticateFromAdmin($row->id);
        return $this->redirect()->toUrl('/');
    }
}
