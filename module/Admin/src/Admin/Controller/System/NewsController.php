<?php

namespace Admin\Controller\System;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class NewsController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('System\News');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'time_add DESC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $filter = new InputFilter\System\News($this->getServiceLocator());
                
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter->setData($request->getPost());
            if ($filter->isValid()) {
                $filter->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        
        $form = new Form\System\News;
        $form->setInputFilter($filter);
        return array(
            'form'  =>  $form
        );
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('System\News')){
            return;
        }
        $filter = new InputFilter\System\News($this->getServiceLocator());
        $filter->setNewsRow($row);     
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter->setData($request->getPost());
            if ($filter->isValid()) {
                $filter->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        
        $form = new Form\System\News;
        $form->setInputFilter($filter);
        return array(
            'form'  =>  $form
        );
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('System\News')){
            return;
        }
        return array(
            'newsRow'   =>  $row
        );
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('System\News')){
            return;
        }
        $table = $this->getTable('System\News');
        $table->delete($row);
        $this->addMessage('Deleted', 'success');
        return $this->_redirect();
    }
}