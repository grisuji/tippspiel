<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 24.01.15
 * Time: 15:06
 */

namespace Application\Form;
use Zend\Form\Form;
use Zend\Debug\Debug;


class WaswennForm extends Form {

    public function __construct($day, $name = null)
    {
        parent::__construct('Waswenn');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $tmp = array();
        for ($d = 1; $d <= $day; $d++) {
            $tmp[strval($d)] = $d.". Spieltag";
        }

        $this->add(array(
                'type' => 'select',
                'name' => 'selected_day',
                'attributes' => array(
                    'onchange' => 'document.forms[1].submit();',
                    'value' => $day //set checked to '1'
                ),
                'options' => array(
                    'label' => 'Spieltag',
                    'value_options' => $tmp
                    ),
                )
        );

    }
}