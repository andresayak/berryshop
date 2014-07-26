<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;

class NpcController extends AbstractController
{
    public function indexAction()
    {
        $rowset = $this->getTable('Lib\Npc')->fetchAll();
        return array(
            'rowset' => $rowset
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\Npc($this->getServiceLocator());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$npcRow = $this->getRow('Lib\Npc')){
            return $this->_redirect();
        }
        $form = new Form\Lib\Npc($this->getServiceLocator());
        $form->setNpcRow($npcRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$npcRow = $this->getRow('Lib\Npc')){
            return $this->_redirect();
        }
        $this->getTable('Lib\Npc')->remove($npcRow);
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$npcRow = $this->getRow('Lib\Npc')){
            return $this->_redirect();
        }
        return array(
            'npcRow' =>  $npcRow,
        );
    }
    
    public function levelAddAction()
    {
        if(!$npcRow = $this->getRow('Lib\Npc')){
            return $this->_redirect();
        }
        $form = new Form\Lib\Npc\Level($this->getServiceLocator());
        $form->setNpcRow($npcRow);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $npcRow->code));
            }
        }
        return array(
            'npcRow' =>  $npcRow,
            'form'      => $form
        );
    }
    
    public function levelEditAction()
    {
        if(!$levelRow = $this->getRow('Lib\Npc\Level')){
            return $this->_redirect();
        }
        
        $form = new Form\Lib\Npc\Level($this->getServiceLocator());
        $form->setLevelRow($levelRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $levelRow->npc_code));
            }
        }
        return array(
            'levelRow'  =>  $levelRow,
            'npcRow'    =>  $levelRow->getNpcRow(),
            'form'      =>  $form
        );
    }
    
    public function levelRemoveAction()
    {
        if(!$levelRow = $this->getRow('Lib\Npc\Level')){
            return;
        }
        $this->getTable('Lib\Npc\Level')->remove($levelRow);
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code' => $levelRow->npc_code));
    }
    
    public function guardAddAction()
    {
        if(!$npcRow = $this->getRow('Lib\Npc')){
            return $this->_redirect();
        }
        $form = new Form\Lib\Npc\Guard($this->getServiceLocator());
        $form->setNpcRow($npcRow);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $npcRow->code));
            }
        }
        return array(
            'npcRow' =>  $npcRow,
            'form'      => $form
        );
    }
    
    public function guardEditAction()
    {
        if(!$guardRow = $this->getRow('Lib\Npc\Guard')){
            return $this->_redirect();
        }
        $form = new Form\Lib\Npc\Guard($this->getServiceLocator());
        $form->setGuardRow($guardRow);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $guardRow->npc_code));
            }
        }
        return array(
            'npcRow'    =>  $guardRow->getNpcRow(),
            'guardRow'  =>  $guardRow,
            'form'      =>  $form
        );
    }
    
    public function guardRemoveAction()
    {
        if(!$guardRow = $this->getRow('Lib\Npc\Guard')){
            return $this->_redirect();
        }
        $this->getTable('Lib\Npc\Guard')->remove($guardRow);
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code' => $guardRow->npc_code));
    }
    
    public function viewLevelAction()
    {
        if(!$levelRow = $this->getRow('Lib/Object/Level')){
            return $this->_redirect();
        }
        return array(
            'levelRow'  =>  $levelRow,
            'objectRow' =>  $levelRow->getObjectRow(),
        );
    }
    
    public function levelLootAddAction()
    {
        if (!$levelRow = $this->getRow('Lib\Npc\Level')) {
            return $this->_redirect();
        }
        $form = new Form\Lib\Npc\Level\Loot($this->getServiceLocator());
        $form->setLevelRow($levelRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $levelRow->npc_code));
            }
        }
        return array(
            'npcRow'    =>  $levelRow->getNpcRow(),
            'levelRow'  =>  $levelRow,
            'form'      =>  $form
        );
    }
    
    public function levelLootEditAction()
    {
        if(!$lootRow = $this->getRow('Lib\Npc\Level\Loot')){
            return;
        }
        $form = new Form\Lib\Npc\Level\Loot($this->getServiceLocator());
        $form->setLootRow($lootRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->finish();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $lootRow->getLevelRow()->npc_code));
            }
        }
        return array(
            'npcRow'    =>  $lootRow->getLevelRow()->getNpcRow(),
            'levelRow'  =>  $lootRow->getLevelRow(),
            'form'      =>  $form
        );
    }
    
    public function levelLootRemoveAction()
    {
        if(!$lootRow = $this->getRow('Lib\Npc\Level\Loot')){
            return;
        }
        $this->getTable('Lib\Npc\Level\Loot')->remove($lootRow);
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code' => $lootRow->getLevelRow()->npc_code));
    }
}
