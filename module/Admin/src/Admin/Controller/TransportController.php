<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Zend\Paginator;

class TransportController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('City\Transport');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'time_end ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
}
