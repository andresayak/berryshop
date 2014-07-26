<?php

namespace Admin\Controller\System;

use Ap\Controller\AbstractController;
use Zend\Paginator;

class FeedbackController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('System\Feedback');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'time_add DESC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
}