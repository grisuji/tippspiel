<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 14.01.15
 * Time: 18:13
 */

namespace Application\Form;
use Zend\Form\Form;

class DeliverForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Deliver');
        $this->setAttribute('method','post');
        $this->setAttribute('enctype','multipart/form-data');

        for ($match = 1; $match < 10; $match++) {
            for ($team = 1; $team <= 2; $team++) {
                $this->add(array(
                        'name' => 'match'.$match.'_team'.$team,
                        'attributes' => array(
                            'type' => 'number',
                        ),
                        'options' => array(
                            'label' => 'Spiel '.$match.' Mannschaft '.$team
                        )
                    )
                );
            }
        }
        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Abschicken'
                ),
            )
        );
    }
}