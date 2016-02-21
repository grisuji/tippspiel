<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Application\Model;
use Application\Rules\GrisujiPoints;
use Application\Rules\ToddePoints;
use Zend\Debug\Debug;


class Match {
    public $id;
    public $start;
    public $day;
    public $team1name;
    public $team2name;
    public $team1goals;
    public $team2goals;
    public $team1halfgoals;
    public $team2halfgoals;
    public $team1emblem;
    public $team2emblem;
    public $isfinished;
    // here comes the tipp-data
    public $tipid;
    public $userid;
    public $username;
    public $team1tip;
    public $team2tip;
    public $istodddetip = false;

    private $points = 0;
    private $toddde = 0;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->team1name = (isset($data['team1name'])) ? $data['team1name'] : null;
        $this->team2name = (isset($data['team2name'])) ? $data['team2name'] : null;
        $this->team1goals = (isset($data['team1goals']) and $data['team1goals']>=0) ? $data['team1goals'] : "";
        $this->team2goals = (isset($data['team2goals']) and $data['team2goals']>=0) ? $data['team2goals'] : "";
        $this->team1halfgoals = (isset($data['team1halfgoals']) and $data['team1halfgoals']>=0) ? $data['team1halfgoals'] : "";
        $this->team2halfgoals = (isset($data['team2halfgoals']) and $data['team2halfgoals']>=0) ? $data['team2halfgoals'] : "";
        $this->team1emblem = (isset($data['team1emblem'])) ? $data['team1emblem'] : null;
        $this->team2emblem = (isset($data['team2emblem'])) ? $data['team2emblem'] : null;
        $this->isfinished = (isset($data['isfinished'])) ? $data['isfinished'] : null;
        $this->tipid = (isset($data['tipid'])) ? $data['tipid'] : null;
        $this->userid = (isset($data['userid'])) ? $data['userid'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->team1tip = (isset($data['team1tip'])) ? $data['team1tip'] : "";
        $this->team2tip = (isset($data['team2tip'])) ? $data['team2tip'] : "";
        $this->start = (isset($data['date_time'])) ? $data['date_time'] : null;
        $this->day = (isset($data['groupid'])) ? $data['groupid'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setPoints($full=true){
        $pointhelper = new GrisujiPoints();
        $toddehelper = new ToddePoints();

        if ($full) {
            $this->points = $pointhelper->getPoints($this->team1goals, $this->team2goals, $this->team1tip, $this->team2tip);
            $this->toddde = $toddehelper->getPoints($this->team1goals, $this->team2goals, $this->team1tip, $this->team2tip);
        } else {
            $this->points = $pointhelper->getPoints($this->team1halfgoals, $this->team2halfgoals, $this->team1tip, $this->team2tip);
            $this->toddde = $toddehelper->getPoints($this->team1halfgoals, $this->team2halfgoals, $this->team1tip, $this->team2tip);
        }
    }

    public function getToddde(){
        return $this->toddde;
    }

    public function getPoints(){
        return $this->points;
    }
}