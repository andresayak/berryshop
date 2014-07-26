<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Service\NavigationFactory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\AbstractNavigationFactory;

class Category extends AbstractNavigationFactory
{
    
    public function getName()
    {
        return 'category';
    }
    
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            $table = $serviceLocator->get('Category\Table');
            $rowset = $table->fetchAllEnabled();
            $pages       = $this->getPagesFromRowset($rowset);
            $this->pages = $this->preparePages($serviceLocator, $pages);
        }
        return $this->pages;
    }
    
    protected function getPagesFromRowset($rowset)
    {
        $config = $this->_initCategory($rowset);
        return $config;
    }
    
    protected function _initCategory($rowset, $parent_id = 0, $factor = 0) 
    {
        $data = array();
        for ($i = 0; $i < count($rowset); $i++) {
            $row = $rowset->getItem($i);
            if ($row->parent_id == $parent_id) {
                $data[] = array(
                    'label'     =>  $row->title,
                    'route'     =>  'else',
                    'params'    =>  array(
                        'uri'   =>  $row->title
                    ),
                    'order'     =>  $row->order,
                    'pages'     =>  $this->_initCategory($rowset, $row->id, $factor+1)
                );
            }
        }
        return $data;
    }
}
