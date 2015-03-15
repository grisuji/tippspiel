<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 13.03.15
 * Time: 17:50
 */

namespace Application\Model;


use Application\Model\UserData;
use Zend\Debug\Debug;

class Saison {
    public $users = array();

    private static function cmp_name($a, $b) {
        $n1 = strtoupper($a["name"]);
        $n2 = strtoupper($b["name"]);
        if ($n1 == $n2) {
            return 0;
        } else {
            return ($n1 < $n2) ? -1 : 1;
        }
    }

    private static function cmp_points($a, $b) {
        if ($a["points_all"] == $b["points_all"]) {
            return Saison::cmp_name($a, $b);
        } else {
            return ($a["points_all"] > $b["points_all"]) ? -1 : 1;
        }
    }

    public function sort($day){
        $a = $this->getUserDataByDay($day);
        usort($a, array($this, "cmp_points"));
        $rank = 0;
        $oldpoints = -1;
        foreach ($a as $u){
            if ($oldpoints != $u["points_all"]) $rank++;
            $user = $this->users[$u["id"]];
            /* @var $user  \Application\Model\UserData */
            $user->setRank($rank, $day, false);
            $oldpoints = $u["points_all"];
        }
    }


    public function addMatch($match){
        $user = $this->users[$match->userid];
        /* @var $user  \Application\Model\UserData */

        if (!isset($user)){
            $user = new UserData($match);
            $this->users[$match->userid] = $user;
        }
        $user->addMatch($match);
    }

    public function setPoints() {
        foreach ($this->users as $u) {
            /* @var $u  \Application\Model\UserData */
            $u->setPoints();
        }
    }

    public function getMatchDataByDay($day) {
        $result = array('matches' => array());

        foreach ($this->users as $u) {
            /* @var $u  \Application\Model\UserData */
            $d = $u->days[$day];
            /* @var $d  \Application\Model\UserDay */
            if (isset($d)) {
                foreach ($d->getMatches() as $m) {
                    /* @var $m  \Application\Model\Match */
                    array_push($result['matches'], $m->id);
                    $result[$m->id] = array();
                    $result[$m->id]['emblem1'] = $m->team1emblem;
                    $result[$m->id]['emblem2'] = $m->team2emblem;
                    if ($m->team1goals >= 0) { # only set, when match started
                        $result[$m->id]['goals1'] = $m->team1goals;
                        $result[$m->id]['goals2'] = $m->team2goals;
                        $result[$m->id]['finished'] = $m->isfinished;
                    }
                }
                return $result;
            }
        }
        return $result;
    }

    # returns an array with the points of all users at a special day
    public function getUserDataByDay($day){
        $result = array();

        foreach ($this->users as $u){
            /* @var $u  \Application\Model\UserData */
            $result[$u->id] = array();
            $result[$u->id]['id'] = $u->id;
            $result[$u->id]['name'] = $u->name;
            $result[$u->id]['rank'] = $u->getRank($day, false);
            $result[$u->id]['toddde'] = $u->getToddde($day);
            $result[$u->id]['points'] = $u->getPoints($day);
            $result[$u->id]['toddde_all'] = $u->getToddde();
            $result[$u->id]['points_all'] = $u->getPoints();
            $result[$u->id]['matches'] = $u->getMatchData($day);
        }
        return $result;
    }
}