<?php

namespace Cms\Model\Page;

use Ap\Model\Table AS Prototype;

class Table extends Prototype
{
    protected $_name = 'cms_page';
    protected $_rowClass = 'Row';
    protected $_tableGateway;  
}