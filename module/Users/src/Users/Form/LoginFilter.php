<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 29.12.14
 * Time: 17:04
 */

namespace Users\Form;
use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
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
            'required'   => true,
        ));
    }
}