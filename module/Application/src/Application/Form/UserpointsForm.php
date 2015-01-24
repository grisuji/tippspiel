<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 24.01.15
 * Time: 15:06
 */

namespace Application\Form;
use Zend\Form\Form;


class UserpointsForm extends Form {

    public function __construct($day, $userid, $userlist, $name = null)
    {
        parent::__construct('Userpoints');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
                'type' => 'hidden',
                'name' => 'hiddenday',
                'attributes' => array(
                    'value' => $day
                )
            )
        );

        $this->add(array(
                'type' => 'hidden',
                'name' => 'hiddenuserid',
                'attributes' => array(
                    'value' => $userid
                )
            )
        );

        $this->add(array(
                'type' => 'select',
                'name' => 'dayselect',
                'attributes' => array(
                    'onchange' => 'document.getElementById("Userpoints").submit();',
                    'value' => $day //set checked to '1'
                ),
                'options' => array(
                    'label' => 'Spieltag',
                    'value_options' => array(
                        '1' => '1. Spieltag',
                        '2' => '2. Spieltag',
                        '3' => '3. Spieltag',
                        '4' => '4. Spieltag',
                        '5' => '5. Spieltag',
                        '6' => '6. Spieltag',
                        '7' => '7. Spieltag',
                        '8' => '8. Spieltag',
                        '9' => '9. Spieltag',
                        '10' => '10. Spieltag',
                        '11' => '11. Spieltag',
                        '12' => '12. Spieltag',
                        '13' => '13. Spieltag',
                        '14' => '14. Spieltag',
                        '15' => '15. Spieltag',
                        '16' => '16. Spieltag',
                        '17' => '17. Spieltag',
                        '18' => '18. Spieltag',
                        '19' => '19. Spieltag',
                        '20' => '20. Spieltag',
                        '21' => '21. Spieltag',
                        '22' => '22. Spieltag',
                        '23' => '23. Spieltag',
                        '24' => '24. Spieltag',
                        '25' => '25. Spieltag',
                        '26' => '26. Spieltag',
                        '27' => '27. Spieltag',
                        '28' => '28. Spieltag',
                        '29' => '29. Spieltag',
                        '30' => '30. Spieltag',
                        '31' => '31. Spieltag',
                        '32' => '32. Spieltag',
                        '33' => '33. Spieltag',
                        '34' => '34. Spieltag'
                    ),
                )
            )
        );

        $this->add(array(
                'type' => 'select',
                'name' => 'useridselect',
                'attributes' => array(
                    'onchange' => 'document.getElementById("Userpoints").submit();',
                    'value' => $userid
                ),
                'options' => array(
                    'label' => 'Spieler',
                    'value_options' => $userlist
                )
            )
        );
    }
}