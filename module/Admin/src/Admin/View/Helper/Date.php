<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class Date extends AbstractHelper
{
    public function __invoke($unixtimestamp) 
    {
        $date = new \DateTime();
        $date->setTimestamp($unixtimestamp);
        return $this->getView()->dateFormat(
                $date,
                \IntlDateFormatter::LONG,
                \IntlDateFormatter::MEDIUM, 'en_EN');
                         
    }
}
