<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 26.01.15
 * Time: 20:14
 */

namespace Application\Controller;
use Application\Form\WaswennForm;
use Application\Model\Saison;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;
use Zend\Json\Expr;
use Zend\Json\Json;
use Zend\Debug\Debug;


class WaswennController  extends AbstractActionController{

    private static function cmp_points($a, $b) {
        if ($a['rank'] == $b['rank']) {
            return WaswennController::cmp_name($a, $b);
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
        $request = $this->getRequest();
        $post = $request->isPost();
        if ($post) {
            $this->day = $this->request->getPost('select');
            if (is_null($this->day)) {
                $this->day = $this->request->getPost('day');
                Debug::dump($this->day);
            }
        }

        $matchTable = $this->getServiceLocator()->get('MatchTable');
        if (is_null($this->day)) {
            $this->day = $matchTable->getDayOfNextMatch();
        }
        $matches = $matchTable->getSaisonTipsAndMatches(2015, "tips");
        $toddde =  $matchTable->getSaisonTipsAndMatches(2015, "todddetips");

        $saison = new Saison();
        $now = new DateTime();
        foreach(array($matches, $toddde) as $set){
            foreach($set as $m) {
                if ($m->userid < 2) continue;
                if ($saison->isMatchSet($m)) continue;
                # hide all future tips
                $start = new DateTime($m->start);
                if ($now->getTimestamp() <= $start->getTimestamp()) {
                    $m->team1tip = "-";
                    $m->team2tip = "-";
                }
                $saison->addMatch($m);
            }
        }


        $saison->fillMissingDays();
        $saison->setPoints(false);
        $saison->sortAllDays();
        $live = $saison->getMatchDataByDay($this->day);
        $user = $saison->getUserDataByDay($this->day);
        usort($user, array($this, "cmp_points"));
        $form = new WaswennForm($this->day);

        $view = new ViewModel(
            array(
                'user'=>$user,
                'day'=>$this->day,
                'live'=>$live,
                'form'=>$form
            ));

        return $view;
    }
}

