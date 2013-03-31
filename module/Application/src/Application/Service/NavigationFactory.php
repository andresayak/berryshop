<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\AbstractNavigationFactory;
/**
 * Constructed factory to set pages during construction.
 */
class NavigationFactory extends AbstractNavigationFactory
{
    protected $_name;
    
    public function __construct($name = 'default')
    {
        $this->_name = $name;
    }
    
    public function getName()
    {
        return $this->_name;
    }
}
