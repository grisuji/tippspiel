<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 26.01.15
 * Time: 20:14
 */

namespace Application\Controller;
use Application\Model\GrisujiPoints;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;
use Zend\Debug\Debug;


class RankController  extends AbstractActionController{

    private static function cmp_points($a, $b) {
        if ($a['points'] == $b['points']) {
            return 0;
        } else {
            return ($a['points'] > $b['points']) ? -1 : 1;
        }
    }

    private static function cmp_name($a, $b) {
        if ($a['name'] == $b['name']) {
            return 0;
        } else {
            return ($a['name'] > $b['name']) ? -1 : 1;
        }
    }

    public function indexAction(){

        $pointhelper = new GrisujiPoints();
        /* @var $m \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
        /* @var $u \Users\Model\User */
        /* @var $userTable \Users\Model\UserTable  */


        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $day = $matchTable->getDayOfNextMatch();
        $matches = $matchTable->getSaisonTipsAndMatches(2014);
        $user = array();
        foreach($matches as $m) {
            if ($m->userid < 2) continue;
            if (!isset($user[$m->userid]))
            {
                $user[$m->userid] = array('name' => $m->username, 'points' => 0 , $day => 0);
            }
            $points = $pointhelper->getPoints($m->team1goals,
                $m->team2goals,
                $m->team1tip,
                $m->team2tip);

            $user[$m->userid][$m->day] += $points;
            $user[$m->userid]['points'] += $points;
        }
        usort($user, array($this,"cmp_name"));
        usort($user, array($this, "cmp_points"));
        $view = new ViewModel(array('user'=>$user, 'day'=>$day));
        return $view;
    }
}