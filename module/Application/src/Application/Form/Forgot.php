<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Validator;
use Zend\InputFilter\InputFilter;

class Forgot extends Form
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
        
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'emailAddress'
                )
            )
        ));
        $this->setInputFilter($inputFilter);
    }
}
