<?php
namespace Users\Form;

use Zend\Form\Form;

class UserEditForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Edit User');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'motto',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Info',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
                'name' => 'password',
                'attributes' => array(
                    'type' => 'password',
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
                ),
                'options' => array(
                    'label' => 'wiederhole Passwort',
                ),
            )
        );
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save'
            ),
        ));
    }
}
