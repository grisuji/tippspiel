<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 14.01.15
 * Time: 18:40
 */

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class DeliverFilter extends InputFilter
{
    public function __construct()
    {

        for ($match = 1; $match < 10; $match++) {
            for ($team = 1; $team <= 2; $team++) {
                $this->add(array(
                    'name' => 'match'.$match.'_team'.$team,
                    'filters' => array(
                        array(
                            'name' => 'StripTags',
                        ),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'Digits',
                        ),
                    ),
                ));
            }
        }
    }
}