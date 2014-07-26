<?php

namespace Admin\Form\Alliance;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class Member extends Form
{
    protected $inputFilter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'user_id',
            'type' => 'text',
        ));
        $this->add(array(
            'name' => 'role',
            'type' => 'select',
            'options'   =>  array(
                'value_options' =>  array(
                    'admin'     =>  'Admin',
                    'moderator' =>  'Moderator',
                    'member'    =>  'Member'
                )
            )
        ));
    }
}

