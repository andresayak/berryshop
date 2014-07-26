<?php

namespace Admin\Controller\System;

use Ap\Controller\AbstractController;

class ExportController extends AbstractController
{
    public function indexAction()
    {
        $type = $this->params()->fromQuery('type', false);
        $types = array(
            'lib_object_cost'   =>array('formClass'=>'BuildCost'),
            'lib_object_attr'   =>array('formClass'=>'BuildAttr'),
            'lib_skill_cost'   =>array('formClass'=>'SkillCost'),
            'lib_skill_attr'   =>array('formClass'=>'SkillAttr'),
            'lib_unit_cost'   =>array('formClass'=>'UnitCost'),
            'lib_unit_attr'   =>array('formClass'=>'UnitAttr'),
        );
        $viewData = array('type'=>$type);
        if(isset($types[$type])){
            $class = 'Admin\Form\System\Export'.$types[$type]['formClass'];
            $form = new $class;
            $form->setSm($this->getServiceLocator());
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $form->saveDataFromCsv();
                    $this->addMessage('Saved');
                    return $this->_redirect('index', array('type'=>$type));
                }
            }
            $viewData['data'] = $form->getDataForCsv();
        }
        return $viewData;
    }
}