<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Validator;
use Zend\InputFilter\InputFilter;

class Signup extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            )
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            )
        ));
        
        $this->add(array(
            'name' => 'password_again',
            'attributes' => array(
                'type'  => 'password',
            )
        ));
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'emailAddress',
                )
            ),
        ));
        
        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
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
        
        $inputFilter->add(array(
            'name' => 'password_again',
            'required' => true,
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
                array(
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'password'
                    ),
                ),
            ),
        ));
        $this->setInputFilter($inputFilter);
    }
    
    public function clearPassword()
    {
        $this->get('password')->setValue('');
        $this->get('password_again')->setValue('');
    }
}
