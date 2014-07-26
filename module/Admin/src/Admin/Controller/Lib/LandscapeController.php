<?php

namespace Admin\Controller\Lib;

use Ap\Controller\AbstractController;
use Admin\Form;

class LandscapeController extends AbstractController
{
    public function indexAction()
    {
        $rowset = $this->getTable('Lib/Landscape')->fetchAll();
        return array(
            'rowset' => $rowset
        );
    }
    
    public function addAction()
    {
        $form = new Form\Lib\LandscapeForm;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getTable('Lib/Landscape')->createRow($form->getData())->save();
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array('form' => $form);
    }
    
    public function editAction()
    {
        if(!$row = $this->getRow('Lib/Landscape')){
            return;
        }
        $form = new Form\Lib\LandscapeForm;
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
    
    public function removeAction()
    {
        if(!$row = $this->getRow('Lib/Landscape')){
            return;
        }
        $row->delete();
        $this->addMessage('Deleted');
        return $this->_redirect();
    }
}
