<?php
namespace Users\Form;

use Zend\InputFilter\InputFilter;

class UserEditFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(
                        'domain' => true,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'filters'    => array(
                array(
                    'name'    => 'StripTags',
                ),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 4,
                        'max'      => 255,
                    ),
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'password',
            'required'   => false,
            'allowEmpty' => true,
        ));

        $this->add(array(
            'name'       => 'confirm_password',
            'required'   => false,
            'allowEmpty' => true,
            'validators' => array(
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            )
        ));
    }
}
