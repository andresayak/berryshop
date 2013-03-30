<?php

namespace Cms\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $table = $this->getServiceLocator()->get('Cms_Page_Table');
        if(!$id = $this->params()->fromRoute('id', false) 
            or !$pageRow = $table->getRowById($id)
        ){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return array(
            'pageRow'  =>  $pageRow,
        );
        
    }
}
