<?php

namespace Shop\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoryController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function viewAction()
    {
        return new ViewModel();
    }
}
