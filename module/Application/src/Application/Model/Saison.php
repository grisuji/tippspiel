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
        $a = $this->getDayPoints($day);
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

    # returns an array with the points of all users at a special day
    private function getDayPoints($day){
        $result = array();
        foreach ($this->users as $u){
            /* @var $u  \Application\Model\User */
            $result["id"] = $u->id;
            $result["name"] = $u->name;
            $result["points"] = $u->getPoints($day);
        }
        return $result;
    }
}