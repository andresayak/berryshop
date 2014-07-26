<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class MarketController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('Market');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway());
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
}
