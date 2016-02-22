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


class RankForm extends Form {

    public function __construct($day, $maxday, $name = null)
    {
        parent::__construct('Rank');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $tmp = array();
        for ($d = 1; $d <= $maxday; $d++) {
            $tmp[strval($d)] = $d.". Spieltag";
        }

        $this->add(array(
                'type' => 'select',
                'name' => 'selected_day',
                'attributes' => array(
                    'onchange' => 'document.getElementById("Rank").submit();',
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