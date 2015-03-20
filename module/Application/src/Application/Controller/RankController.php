<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 26.01.15
 * Time: 20:14
 */

namespace Application\Controller;
use Application\Model\Saison;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;
use Zend\Debug\Debug;


class RankController  extends AbstractActionController{

    private static function cmp_points($a, $b) {
        if ($a['rank'] == $b['rank']) {
            return RankController::cmp_name($a, $b);
        } else {
            return ($a['rank'] < $b['rank']) ? -1 : 1;
        }
    }

    private static function cmp_name($a, $b) {
        $n1 = strtoupper($a['name']);
        $n2 = strtoupper($b['name']);
        if ($n1 == $n2) {
            return 0;
        } else {
            return ($n1 < $n2) ? -1 : 1;
        }
    }

    public function indexAction(){
        /* @var $m \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
        /* @var $u \Users\Model\User */
        /* @var $userTable \Users\Model\UserTable  */


        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $day = $matchTable->getDayOfNextMatch();
        $matches = $matchTable->getSaisonTipsAndMatches(2014);

        $saison = new Saison();
        $now = new DateTime();
        foreach($matches as $m) {
            if ($m->userid < 2) continue;
            # hide all future tips
            $start = new DateTime($m->start);
            if ($now->getTimestamp() <= $start->getTimestamp()) {
                $m->team1tip = "-";
                $m->team2tip = "-";
            }
            $saison->addMatch($m);
        }
        $saison->fillMissingDays();
        $saison->setPoints();
        $saison->sortAllDays();
        $live = $saison->getMatchDataByDay($day);
        $user = $saison->getUserDataByDay($day);
        usort($user, array($this, "cmp_points"));
        #Debug::dump($live );
        #Debug::dump($user);
        #Debug::dump($saison->getDays($day));
        #Debug::dump("---");
        #Debug::dump($saison->getHighchartUserRanks($day));
        $hc_yaxis_data = json_encode($saison->getHighchartUserRanks($day));
        $hc_xaxis_data = json_encode($saison->getDays($day));
        $view = new ViewModel(
            array(
                'user'=>$user,
                'day'=>$day,
                'live'=>$live,
                'hc_yaxis_data'=>$hc_yaxis_data,
                'hc_xaxis_data'=>$hc_xaxis_data
            ));

        return $view;
    }
}