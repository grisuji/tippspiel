<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 29.12.14
 * Time: 16:20
 */

namespace Users\Form;
use Zend\Form\Form;


class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        // add all the fields
        $this->add(array(
                'name' => 'name',
                'attributes' => array(
                    'type' => 'text',
                    'required' => 'required',
                ),
                'options' => array(
                    'label' => 'Spielername',
                )
            )
        );
        $this->add(array(
                'name' => 'password',
                'attributes' => array(
                    'type' => 'password',
                    'required' => 'required',
                ),
                'options' => array(
                    'label' => 'Passwort',
                ),
            )
        );
        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Einloggen'
                ),
            )
        );
    }
}