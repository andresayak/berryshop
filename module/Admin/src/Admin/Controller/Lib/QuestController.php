<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;

class QuestController extends AbstractController
{
    
    public function indexAction()
    {
        $table = $this->getTable('Lib\Quest');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'position ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(1000);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\Quest($this->getServiceLocator());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    
    public function editAction()
    {
        if(!$questRow = $this->getRow('Lib\Quest')){
            return;
        }
        $form = new Form\Lib\Quest($this->getServiceLocator());
        $form->setQuestRow($questRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$questRow = $this->getRow('Lib\Quest')){
            return;
        }
        $questRow->delete();
        $this->addMessage('Deleted', 'error');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$questRow = $this->getRow('Lib\Quest')){
            return;
        }
        
        return array(
            'questRow' =>  $questRow,
        );
    }
    
    public function addNeedAction()
    {
        if(!$questRow = $this->getRow('Lib\Quest')){
            return;
        }
        $form = new Form\Lib\Quest\Need($this->getServiceLocator(), $questRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect('index');
            }
        }
        return array(
            'questRow'  =>  $questRow,
            'form'      =>  $form
        );
    }
    
    public function editNeedAction()
    {
        if(!$needRow = $this->getRow('Lib\Quest\Need')){
            return;
        }
        
        $form = new Form\Lib\Quest\Need($this->getServiceLocator(), $needRow->getQuestRow(), $needRow);
        $form->setData($needRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $needRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved', 'success');
                return $this->_redirect('index', array('code' => $needRow->object_code));
            }
        }
        return array(
            'needRow'   =>  $needRow,
            'questRow'  =>  $needRow->getQuestRow(),
            'form'      =>  $form
        );
    }
    
    public function removeNeedAction()
    {
        if(!$needRow = $this->getRow('Lib\Quest\Need')){
            return;
        }
        $needRow->delete();
        $this->addMessage('Deleted', 'success');
        return $this->_redirect('index', array('code' => $needRow->quest_id));
    }
    
    public function addDependAction()
    {
        if(!$questRow = $this->getRow('Lib\Quest')){
            return;
        }
        $form = new Form\Lib\Quest\Depend($this->getServiceLocator(), $questRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect('index');
            }
        }
        return array(
            'questRow'  =>  $questRow,
            'form'      =>  $form
        );
    }
    
    public function editDependAction()
    {
        if(!$dependRow = $this->getRow('Lib\Quest\Depend')){
            return;
        }
        
        $form = new Form\Lib\Quest\Need($this->getServiceLocator(), $dependRow->getQuestRow(), $dependRow);
        $form->setData($dependRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $dependRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved', 'success');
                return $this->_redirect('index', array('code' => $dependRow->object_code));
            }
        }
        return array(
            'dependRow' =>  $dependRow,
            'questRow'  =>  $dependRow->getQuestRow(),
            'form'      =>  $form
        );
    }
    
    public function removeDependAction()
    {
        if(!$dependRow = $this->getRow('Lib\Quest\Depend')){
            return;
        }
        $dependRow->delete();
        $this->addMessage('Deleted', 'success');
        return $this->_redirect('index', array('code' => $dependRow->quest_id));
    }
    
    public function addRewardAction()
    {
        if(!$questRow = $this->getRow('Lib\Quest')){
            return;
        }
        $form = new Form\Lib\Quest\Reward($this->getServiceLocator(), $questRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved', 'success');
                return $this->_redirect('index');
            }
        }
        return array(
            'questRow'  =>  $questRow,
            'form'      =>  $form
        );
    }
    
    public function editRewardAction()
    {
        if(!$rewardRow = $this->getRow('Lib\Quest\Reward')){
            return;
        }
        $form = new Form\Lib\Quest\Reward($this->getServiceLocator(), $rewardRow->getQuestRow(), $rewardRow);
        $form->setData($rewardRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $rewardRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved', 'success');
                return $this->_redirect('index', array('code' => $rewardRow->object_code));
            }
        }
        return array(
            'rewardRow'   =>  $rewardRow,
            'questRow'  =>  $rewardRow->getQuestRow(),
            'form'      =>  $form
        );
    }
    
    public function removeRewardAction()
    {
        if(!$rewardRow = $this->getRow('Lib\Quest\Reward')){
            return;
        }
        $rewardRow->delete();
        $this->addMessage('Deleted', 'success');
        return $this->_redirect('index', array('code' => $rewardRow->quest_id));
    }
}
