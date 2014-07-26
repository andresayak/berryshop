<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;

class LevelAllianceController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('Lib\Level\Alliance');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'level ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\LevelAllianceForm;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getTable('Lib\Level\Alliance')->getTableGateway()->insert($form->getData());
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Lib\Level\Alliance')){
            return $this->_redirect();
        }
        $form = new Form\Lib\LevelAllianceForm;
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
        if(!$row = $this->getRow('Lib\Level\Alliance')){
            return;
        }
        $row->delete();
        $this->addMessage('Deleted', 'success');
        return $this->_redirect();
    }
}
