<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;
use Admin\Form;
use Zend\Paginator;
use Game\Form\InputFilter;

class EventController extends AbstractController
{
    public function indexAction()
    {
        $table = $this->getTable('City\Event');
        $adapter = new Paginator\Adapter\DbTableGateway($table->getTableGateway(), null, 'time_end ASC');
        $paginator = new Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p',1))
            ->setItemCountPerPage(20);
        return array(
            'paginator' => $paginator
        );
    }
    
    public function endAction()
    {
        $eventTable = $this->getServiceLocator()->get('City\Event\Table');
        if(!$event_id = $this->params()->fromQuery('id') 
            or !$eventRow = $eventTable->fetchBy('id', $event_id)
        ){
            return $this->_redirect();
        }
        $adapter = $eventTable->getTableGateway()->getAdapter();
        $profiler = new \Zend\Db\Adapter\Profiler\Profiler();
        $adapter->setProfiler($profiler);
        
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();
        try {
            $eventRow->getCityRow()->updateEvents(time(), $eventRow->id);
            $eventRow->getCityRow()->updateAttributes();
            foreach($eventRow->getCityRow()->getObjectRowset()->getItems() AS $objectRow){
                $objectRow->save();
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw new \Exception('Invalid transaction', 0, $e);
        }
        
        return $this->_redirect();
        
        $queryProfiles = $profiler->getProfiles();
        
        foreach($queryProfiles as $key=>$row)
        {
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }
        
        exit;
    }
}
