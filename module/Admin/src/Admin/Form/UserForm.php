<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class UserForm extends Form
{
    protected $inputFilter;
    protected $_user_table;
    public function __construct($userTable, $userRow = false)
    {
        $this->_user_table = $userTable;
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'username',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
        ));
        if(!$userRow)
            $this->add(array(
                'name' => 'password',
                'type' => 'text',
            ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'confirm_status',
            'options' => array(
                'value_options' => array(
                    'on'    =>  'On',
                    'off'   =>  'Off'
                )
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'role',
            'options' => array(
                'value_options' => array(
                    'user'    =>  'User',
                    'admin'   =>  'Admin'
                )
            ),
        ));
        
        $this->add(array(
            'type' => 'select',
            'name' => 'ban_status',
            'options' => array(
                'value_options' => array(
                    'on'    =>  'On',
                    'off'   =>  'Off'
                )
            ),
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'username',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                new \Zend\Validator\Db\NoRecordExists(
                    array(
                        'adapter'   =>  $this->getUserTable()->getTableGateway()->getAdapter(),
                        'table'     =>  $this->getUserTable()->getName(),
                        'field'     =>  'username',
                        'exclude'   =>  (($userRow)?'id!='.$userRow->id:null)
                    )
                )
            )
        ));
        $inputFilter->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'EmailAddress'),
                new \Zend\Validator\Db\NoRecordExists(
                    array(
                        'adapter'   =>  $this->getUserTable()->getTableGateway()->getAdapter(),
                        'table'     =>  $this->getUserTable()->getName(),
                        'field'     =>  'email',
                        'exclude'   =>  (($userRow)?'id!='.$userRow->id:null)
                    )
                )
            )
        ));
        if(!$userRow)
            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'string_length',
                        'options' => array(
                            'min' => 6
                        ),
                    ),
                ),
            ));
        
        $this->setInputFilter($inputFilter);
        
    }
    
    public function getUserTable()
    {
        return $this->_user_table;
    }
}

