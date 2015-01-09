<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 29.12.14
 * Time: 11:06
 */

namespace Users\Form;
use Zend\Form\Form;

class RegisterForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
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
                'name' => 'email',
                'attributes' => array(
                    'type' => 'email',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Email'
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
                'name' => 'confirm_password',
                'attributes' => array(
                    'type' => 'password',
                    'required' => 'required',
                ),
                'options' => array(
                    'label' => 'wiederhole Passwort',
                ),
            )
        );
        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Registrieren'
                ),
            )
        );

    }

} 