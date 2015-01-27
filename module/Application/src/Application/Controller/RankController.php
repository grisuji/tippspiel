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

    public function indexAction(){

        $pointhelper = new GrisujiPoints();
        /* @var $m \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
        /* @var $u \Users\Model\User */
        /* @var $userTable \Users\Model\UserTable  */


        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $matches = $matchTable->getSaisonMatches(2014);
        Debug::dump($matches);
        exit;
        $user = array();
        foreach($matches as $m) {
            if (!isset($user[$m->userid]))
            {
                $user[$m->userid] = array('name' => $m->username, 'points' => 0);
            }
            $user[$m->userid]['points'] += $pointhelper->getPoints($m->team1goals,
                                                            $m->team2goals,
                                                            $m->team1tip,
                                                            $m->team2tip);
        }
        $view = new ViewModel(array('user'=>$user));
        return $view;
    }
}