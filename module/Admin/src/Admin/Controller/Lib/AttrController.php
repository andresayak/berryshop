<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;

class AttrController extends AbstractController
{
    public function indexAction()
    {
        $rowset = $this->getTable('Lib/Attr')->fetchAll();
        return array(
            'rowset' => $rowset
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\AttrForm;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('Lib/Attr');
                $attrRow = $table->createRow($form->getData());
                $table->add($attrRow);
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Lib/Attr', 'code')){
            return $this->_redirect();
        }
        $form = new Form\Lib\AttrForm;
        $form->get('code')->setAttribute('disabled', true);
        $form->getInputFilter()->remove('code');
        $form->setData($row->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getTable('Lib/Attr');
                $row->setFromArray($form->getData());
                $table->edit($row);
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Lib/Attr', 'code')){
            return;
        }
        $table = $this->getTable('Lib/Attr');
        $table->remove($row);
        $this->addMessage('Deleted', 'success');
        return $this->_redirect();
    }
}
