<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;

class ObjectController extends AbstractController
{
    public function indexAction()
    {
        $rowset = $this->getTable('Lib/Object')->fetchAll();
        return array(
            'rowset' => $rowset
        );
    }
    
    public function addAction()
    {
        $type = $this->params()->fromQuery('type');
        $form = new Form\Lib\Object($type);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $table = $this->getTable('Lib/Object');
                $objectRow = $table->createRow($data);
                $objectRow->type = $type;
                $table->add($objectRow);
                if($objectRow->isTime()){
                    $table = $this->getServiceLocator()->get('Lib\Object\Level\Table');
                    $levelRow = $table->createRow(array(
                        'level'         =>  1,
                        'object_code'   =>  $objectRow->code,
                        'time_build'    =>  $data['time_build']
                    ));
                    $levelRow->save();
                }
                $this->addMessage('Saved');
                return $this->_redirect();
            }else{
                $this->addMessage($form->getInputFilter(), 'error');
                return $this->_redirect();
            }
        }
        return array('form' => $form, 'type'=>$type);
    }
    
    
    public function editAction()
    {
        if(!$objectRow = $this->getRow('Lib/Object')){
            return;
        }
        $form = new Form\Lib\Object($objectRow->type);
        $form->get('code')->setAttribute('disabled', true);
        $form->getInputFilter()->remove('code');
        $data = $objectRow->toArray();
        if($objectRow->isTime()){
            $levelRow = $objectRow->getLevelRowset()->getBy('level',1);
            $data['time_build'] = $levelRow->time_build;
        }
        $form->setData($data);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $objectRow->setFromArray($data)
                    ->save();
                if($objectRow->isTime()){
                    $levelRow->time_build = $data['time_build'];
                    $levelRow->save();
                }
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form, 'type'=>$objectRow->type);
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Lib/Object')){
            return;
        }
        $row->delete();
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
    
    public function viewAction()
    {
        if(!$row = $this->getRow('Lib/Object')){
            return;
        }
        if($row->type == 'unit'){
            if(!$row->getLevelRowset()->getBy('level', 1)){
                $table = $this->getServiceLocator()->get('Lib\Object\Level\Table');
                $levelRow = $table->createRow(array(
                    'level' =>  1,
                    'object_code'   =>  $row->code,
                    'time_build'    =>  0
                ));
                $levelRow->save();
                $row->getLevelRowset()->add($levelRow);
            }
        }
        
        return array(
            'objectRow' =>  $row,
        );
    }
    
    public function addLevelAction()
    {
        if(!$objectRow = $this->getRow('Lib/Object')){
            return;
        }
        $form = new Form\Lib\Object\LevelForm();
        $form->get('level')->setValue($objectRow->getLevelRowset()->maxValue('level')+1);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('Lib/Object/Level');
                $levelRow = $table->createRow($form->getData());
                $levelRow->object_code = $objectRow->code;
                $table->add($levelRow);
                
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$objectRow->code));
            }
        }
        return array(
            'objectRow' =>  $objectRow,
            'form' => $form
        );
    }
    
    public function editLevelAction()
    {
        if(!$levelRow = $this->getRow('Lib/Object/Level')){
            return;
        }
        
        $form = new Form\Lib\Object\LevelForm;
        $form->setData($levelRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $levelRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code' => $levelRow->object_code));
            }
        }
        return array(
            'levelRow'  =>  $levelRow,
            'objectRow' =>  $levelRow->getObjectRow(),
            'form'      =>  $form
        );
    }
    
    public function removeLevelAction()
    {
        if(!$levelRow = $this->getRow('Lib/Object/Level')){
            return;
        }
        $levelRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code' => $levelRow->object_code));
    }
    
    
    public function viewLevelAction()
    {
        if(!$levelRow = $this->getRow('Lib/Object/Level')){
            return;
        }
        return array(
            'levelRow'  =>  $levelRow,
            'objectRow' =>  $levelRow->getObjectRow(),
        );
    }
    
    public function addLevelGiveAction()
    {
        if(!$objectRow = $this->getRow('Lib/Object', 'object_code')){
            if(!$levelRow = $this->getRow('Lib/Object/Level'))
                return;
        }else{
            $levelRow = $objectRow->getLevelRowset()->getBy('level', 1);
        }
        $objectRowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Lib\Object\Level\Give($objectRowset, $levelRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $giveRow = $this->getTable('Lib/Object/Level/Give')->createRow($form->getData());
                $giveRow->level_id = $levelRow->id;
                $giveRow->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$levelRow->object_code));
            }
        }
        return array(
            'objectRow' =>  $levelRow->getObjectRow(),
            'levelRow'  =>  $levelRow,
            'form'      =>  $form
        );
    }
    
    public function editLevelGiveAction()
    {
        if(!$giveRow = $this->getRow('Lib/Object/Level/Give')){
            return;
        }
        $objectRowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Lib\Object\Level\Give($objectRowset, $giveRow->getLevelRow(), $giveRow);
        $form->setData($giveRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $giveRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$giveRow->getLevelRow()->object_code));
            }
        }
        return array(
            'levelRow'  =>  $giveRow->getLevelRow(),
            'objectRow' =>  $giveRow->getLevelRow()->getObjectRow(),
            'form'      =>  $form
        );
    }
    
    public function removeLevelGiveAction()
    {
        if(!$giveRow = $this->getRow('Lib/Object/Level/Give')){
            return;
        }
        $giveRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code' => $giveRow->getLevelRow()->object_code));
    }
    
    public function addLevelNeedAction()
    {
        if(!$objectRow = $this->getRow('Lib/Object', 'object_code')){
            if(!$levelRow = $this->getRow('Lib/Object/Level'))
                return;
        }else{
            $levelRow = $objectRow->getLevelRowset()->getBy('level', 1);
        }
        $objectRowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Lib\Object\Level\NeedForm($objectRowset, $levelRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $needRow = $this->getTable('Lib/Object/Level/Need')->createRow($form->getData());
                $needRow->level_id = $levelRow->id;
                $needRow->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$levelRow->object_code));
            }
        }
        return array(
            'objectRow' =>  $levelRow->getObjectRow(),
            'levelRow'  =>  $levelRow,
            'form'      =>  $form
        );
    }
    
    public function editLevelNeedAction()
    {
        if(!$needRow = $this->getRow('Lib/Object/Level/Need')){
            return;
        }
        $objectRowset = $this->getServiceLocator()->get('Lib\Object\Rowset');
        $form = new Form\Lib\Object\Level\NeedForm($objectRowset, $needRow->getLevelRow(), $needRow);
        $form->setData($needRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $needRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$needRow->getLevelRow()->object_code));
            }
        }
        return array(
            'levelRow'  =>  $needRow->getLevelRow(),
            'objectRow' =>  $needRow->getLevelRow()->getObjectRow(),
            'form'      =>  $form
        );
    }
    
    public function removeLevelNeedAction()
    {
        if(!$needRow = $this->getRow('Lib/Object/Level/Need')){
            return;
        }
        $needRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code'=>$needRow->getLevelRow()->object_code));
    }
    
    public function addLevelAttrAction()
    {
        if(!$objectRow = $this->getRow('Lib/Object', 'object_code')){
            if(!$levelRow = $this->getRow('Lib/Object/Level'))
                return;
        }else{
            $levelRow = $objectRow->getLevelRowset()->getBy('level', 1);
        }
        $form = new Form\Lib\Object\Level\AttrForm($this->getServiceLocator(), $levelRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $needRow = $this->getTable('Lib/Object/Level/Attr')->createRow($form->getData());
                $needRow->level_id = $levelRow->id;
                $needRow->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$levelRow->object_code));
            }
        }
        return array(
            'objectRow' =>  $levelRow->getObjectRow(),
            'levelRow'  =>  $levelRow,
            'form'      =>  $form
        );
    }
    
    public function editLevelAttrAction()
    {
        if(!$attrValueRow = $this->getRow('Lib/Object/Level/Attr')){
            return;
        }
        $form = new Form\Lib\Object\Level\AttrForm($this->getServiceLocator(), $attrValueRow->getLevelRow(), $attrValueRow);
        $form->setData($attrValueRow->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $attrValueRow->setFromArray($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect('view', array('code'=>$attrValueRow->getLevelRow()->object_code));
            }
        }
        return array(
            'levelRow'  =>  $attrValueRow->getLevelRow(),
            'objectRow' =>  $attrValueRow->getLevelRow()->getObjectRow(),
            'form'      =>  $form
        );
    }
    
    public function removeLevelAttrAction()
    {
        if(!$attrValueRow = $this->getRow('Lib/Object/Level/Attr')){
            return;
        }
        $attrValueRow->delete();
        $this->addMessage('Deleted');
        return $this->_redirect('view', array('code'=>$attrValueRow->getLevelRow()->object_code));
    }
}
