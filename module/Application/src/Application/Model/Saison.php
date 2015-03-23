<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 13.03.15
 * Time: 17:50
 */

namespace Application\Model;
use DateTime;


use Application\Model\UserData;
use Zend\Debug\Debug;

class Saison {
    public $users = array();
    public $days = array();

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
        $mempoints = -1;
        foreach ($a as $u){
            $rank++;
            if ($mempoints != $u["points_all"]) $setrank=$rank;
            $user = $this->users[$u["id"]];
            /* @var $user  \Application\Model\UserData */
            $user->setRank($setrank, $day, false);
            $mempoints = $u["points_all"];
        }
    }

    public function sortAllDays(){
        foreach ($this->days as $day) {
            $this->sort($day);
        }
    }

    /* @var $match  \Application\Model\Match */
    public function addMatch($match){
        $user = $this->users[$match->userid];
        /* @var $user  \Application\Model\UserData */
        $day = intval($match->day);
        if (!in_array($day, $this->days)) {
            $this->days[] = $day;
            natsort($this->days);
        }

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
                $now = new DateTime();
                foreach ($d->getMatches() as $m) {
                    /* @var $m  \Application\Model\Match */
                    if (isset($result[$m->id])) continue;
                    array_push($result['matches'], $m->id);
                    $result[$m->id] = array();
                    $result[$m->id]['start'] = $m->start;
                    $result[$m->id]['name1'] = $m->team1name;
                    $result[$m->id]['name2'] = $m->team2name;
                    $result[$m->id]['emblem1'] = $m->team1emblem;
                    $result[$m->id]['emblem2'] = $m->team2emblem;
                    $start = new DateTime($m->start);
                    if ($now->getTimestamp() >= $start->getTimestamp()) { # only set, when match started
                        $result[$m->id]['goals1'] = $m->team1goals;
                        $result[$m->id]['goals2'] = $m->team2goals;
                        $result[$m->id]['finished'] = $m->isfinished;
                    }
                }
                #return $result;
            }
        }
        return $result;
    }

    # returns an array with the points of all users at a special day
    public function getUserDataByDay($xday){
        $result = array();

        foreach ($this->users as $u){
            /* @var $u  \Application\Model\UserData */
            $day = $u->getNearestDay($xday);
            $result[$u->id] = array();
            $result[$u->id]['id'] = $u->id;
            $result[$u->id]['name'] = $u->name;
            $result[$u->id]['rank'] = $u->getRank($day, false);
            $result[$u->id]['toddde'] = $u->getToddde($day, true);
            $result[$u->id]['points'] = $u->getPoints($day, true);
            $result[$u->id]['toddde_all'] = $u->getToddde($day);
            $result[$u->id]['points_all'] = $u->getPoints($day);
            $result[$u->id]['matches'] = $u->getMatchData($day);
        }
        return $result;
    }

    public function getDays($maxday=0){
        if ($maxday == 0) return $this->days;
        $result=array();
        foreach ($this->days as $day) {
            if ($day > $maxday) continue;
            $result[] = $day;
        }
        return $result;
    }

    public function getHighchartUserRanks($maxday){
        $result = array();
        foreach ($this->users as $u ) {
            /* @var $u  \Application\Model\UserData */
            $data = array();
            foreach ($u->days as $d) {
                $day = $d->day;
                if ($day > $maxday) continue;
                $rank = $u->getRank($day, false);
                $points = $u->getPoints($day, false);
                $data[] = array( 'x' => $day , 'y' => $rank, 'z' => $points);
            }
            $lastset = end($data);
            if ($lastset['x']!=$maxday) {
                $data[] = array ( 'x' => $maxday, 'y' => $lastset['y'], 'z' => $lastset['z']);
            }
            $new_user = array(
                'name' => $u->name,
                'data' => $data
                );
            $result[] = $new_user;
        }
        return $result;
    }

    public function getHighChartTipPoints($maxday, $wonpoint=true) {
        $result = array();
        foreach ($this->users as $u ) {
            /* @var $u  \Application\Model\UserData */
            $pointmatrix = array(array());
            $matchmatrix = array(array());

            for ($i=0;$i<=6;$i++){
                for ($j=0;$j<=6;$j++){
                    $pointmatrix[$i][$j] = 0;
                    $matchmatrix[$i][$j] = 0;
                }
            }
            foreach ($u->days as $d) {
                /* @var $d  \Application\Model\UserDay */
                $day = $d->day;
                if ($day > $maxday) continue;
                $matches = $d->getMatches();
                foreach ($matches as $m) {
                    /* @var $m \Application\Model\Match */
                    if ($m->team1goals === "" or $m->team2goals === "" or $m->team1tip === "" or $m->team2tip === "") continue;
                    $tip1=min(intval($m->team1tip), 6);
                    $tip2=min(intval($m->team2tip), 6);
                    if ($wonpoint){
                        $pointmatrix[$tip1][$tip2] += $m->getPoints();
                    } else {
                        $pointmatrix[$tip1][$tip2] += (8-$m->getPoints());
                    }

                    $matchmatrix[$tip1][$tip2]++;
                }
            }
            $data=array();
            for ($i=0;$i<=6;$i++){
                for ($j=0;$j<=6;$j++){
                    #$data[] = array("x" => $i, "y" => $j, "z" => $pointmatrix[$i][$j],$matchmatrix[$i][$j]);
                    $data[] = array( $i, $j,  $pointmatrix[$i][$j]);
                }
            }

            $new_user = array(
                'name' => $u->name,
                'data' => $data
            );
            $result[$u->id] = $new_user;
        }
        return $result;
    }

    public function getHighChartResultPoints($maxday, $wonpoint) {
        $result = array();
        foreach ($this->users as $u ) {
            /* @var $u  \Application\Model\UserData */
            $pointmatrix = array(array());
            $matchmatrix = array(array());

            for ($i=0;$i<=6;$i++){
                for ($j=0;$j<=6;$j++){
                    $pointmatrix[$i][$j] = 0;
                    $matchmatrix[$i][$j] = 0;
                }
            }
            foreach ($u->days as $d) {
                /* @var $d  \Application\Model\UserDay */
                $day = $d->day;
                if ($day > $maxday) continue;
                $matches = $d->getMatches();
                foreach ($matches as $m) {
                    /* @var $m \Application\Model\Match */
                    if ($m->team1goals === "" or $m->team2goals === "" or $m->team1tip === "" or $m->team2tip === "") continue;
                    $result1=min(intval($m->team1goals), 6);
                    $result2=min(intval($m->team2goals), 6);
                    if ($wonpoint){
                        $pointmatrix[$result1][$result2] += $m->getPoints();
                    } else {
                        $pointmatrix[$result1][$result2] += (8-$m->getPoints());
                    }
                    $matchmatrix[$result1][$result2]++;
                }
            }
            $data=array();
            for ($i=0;$i<=6;$i++){
                for ($j=0;$j<=6;$j++){
                    #$data[] = array("x" => $i, "y" => $j, "z" => $pointmatrix[$i][$j],$matchmatrix[$i][$j]);
                    $data[] = array( $i, $j, $pointmatrix[$i][$j]);
                }
            }

            $new_user = array(
                'name' => $u->name,
                'data' => $data
            );
            $result[$u->id] = $new_user;
        }
        return $result;
    }

    public function fillMissingDays(){
        foreach ($this->users as $u) {
            /* @var $u  \Application\Model\UserData */
            $u->fillDummyDays(end($this->days));
        }
    }
}