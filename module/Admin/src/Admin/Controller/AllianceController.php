<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class AllianceController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('Alliance');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'id ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $filter = new InputFilter\Alliance\Add($this->getServiceLocator());
        $filter->setUserFromCheck(true);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter->setData($request->getPost());
            if ($filter->isValid()) {
                $filter->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        
        $form = new Form\Alliance;
        $form->setInputFilter($filter);
        return array(
            'form'  =>  $form
        );
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Alliance')){
            return;
        }
        $filter = new InputFilter\Alliance\Edit($this->getServiceLocator());
        $filter->setUserFromCheck(true);
        $filter->setAllianceRow($row);   
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter->setData($this->params()->fromPost() + array('alliance_id'=>$row->id));
            if ($filter->isValid()) {
                $filter->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        
        $form = new Form\Alliance;
        $form->setInputFilter($filter);
        return array(
            'form'  =>  $form
        );
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('Alliance')){
            return;
        }
        return array(
            'allianceRow'   =>  $row
        );
    }
    
    public function removeAction()
    {
        $filter = new InputFilter\Alliance\Remove($this->getServiceLocator());
        $filter->setUserFromCheck(true);
        $filter->setData(array('alliance_id'=>$this->params()->fromQuery('id', false)));
        if($filter->isValid()){
            $filter->finish();
            $this->addMessage('Deleted', 'success');
        }else{
            $this->addMessage($filter, 'error');
        }
        return $this->_redirect();
    }
}
