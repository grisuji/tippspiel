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

        for ($match = 1; $match <= 9; $match++) {
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
 #       $this->add(array(
 #           'name' => 'select',
 #           'attributes' => array(
 #               'type' => 'select',
 #               'value' => 'Spieltag'
 #               ),
 #           'options' => array(
 #               '1' => '1. Spieltag',
 #               '2' => '2. Spieltag',
 #               '3' => '3. Spieltag',
 #               '4' => '4. Spieltag',
 #               '5' => '5. Spieltag',
 #               '6' => '6. Spieltag',
 #               '7' => '7. Spieltag',
 #               '8' => '8. Spieltag',
 #               '9' => '9. Spieltag',
 #               '10' => '10. Spieltag',
 #               '11' => '11. Spieltag',
 #               '12' => '12. Spieltag',
 #               '13' => '13. Spieltag',
 #               '14' => '14. Spieltag',
 #               '15' => '15. Spieltag',
 #               '16' => '16. Spieltag',
 #               '17' => '17. Spieltag',
 #               '18' => '18. Spieltag',
 #               '19' => '19. Spieltag',
 #               '20' => '20. Spieltag',
 #               '21' => '21. Spieltag',
 #               '22' => '22. Spieltag',
 #               '23' => '23. Spieltag',
 #               '24' => '24. Spieltag',
 #               '25' => '25. Spieltag',
 #               '26' => '26. Spieltag',
 #               '27' => '27. Spieltag',
 #               '28' => '28. Spieltag',
 #               '29' => '29. Spieltag',
 #               '30' => '30. Spieltag',
 #               '31' => '31. Spieltag',
 #               '32' => '32. Spieltag',
 #               '33' => '33. Spieltag',
 #               '34' => '34. Spieltag'
 #               )
 #           )
 #       );

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