<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 13.03.15
 * Time: 17:50
 */

namespace Application\Model;


use Users\Model\User;

class Saison {
    public $users = array();
    public $day_to_sort;

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
        if ($a["points"] == $b["points"]) {
            return Saison::cmp_name($a, $b);
        } else {
            return ($a["points"] > $b["points"]) ? -1 : 1;
        }
    }

    public function sort($day){
        $this->day_to_sort = $day;
        $a = $this->getUserDataByDay($day);
        usort($a, array($this, "cmp_points"));
        $rank = 0;
        $oldpoints = -1;
        foreach ($a as $u){
            if ($oldpoints != $u["points"]) $rank++;
            /* @var $u  \Application\Model\User */
            $u->setRank($rank, $day, false);
        }

    }

    public function addMatch($match){
        if (!isset($this->users[$match->userid])){
            $user = new User($match);
            $this->users[$match->userid] = $user;
        }

    }


    public function getMatchDataByDay($day) {
        $result = array();
        foreach ($this->users as $u) {
            /* @var $u  \Application\Model\User */
            $d = $u->days[$day];
            /* @var $d  \Application\Model\UserDay */
            if (isset($d)) {
                foreach ($d->getMatches() as $m) {
                    /* @var $m  \Application\Model\Match */
                    array_push($result['matches'], $m->id);
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
            /* @var $u  \Application\Model\User */
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