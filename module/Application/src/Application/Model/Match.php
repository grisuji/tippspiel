<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Application\Model;

class Match {
    public $id;
    public $start;
    public $team1name;
    public $team2name;
    public $team1goals;
    public $team2goals;
    public $team1emblem;
    public $team2emblem;
    public $isfinished;
    public $tipid;
    public $userid;
    public $team1tip;
    public $team2tip;
    public $day;
    public $points;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->team1name = (isset($data['team1name'])) ? $data['team1name'] : null;
        $this->team2name = (isset($data['team2name'])) ? $data['team2name'] : null;
        $this->team1goals = (isset($data['team1goals']) and $data['team1goals']>=0) ? $data['team1goals'] : "";
        $this->team2goals = (isset($data['team2goals']) and $data['team2goals']>=0) ? $data['team2goals'] : "";
        $this->team1emblem = (isset($data['team1emblem'])) ? $data['team1emblem'] : null;
        $this->team2emblem = (isset($data['team2emblem'])) ? $data['team2emblem'] : null;
        $this->isfinished = (isset($data['isfinished'])) ? $data['isfinished'] : null;
        $this->tipid = (isset($data['tipid'])) ? $data['tipid'] : null;
        $this->userid = (isset($data['userid'])) ? $data['userid'] : null;
        $this->team1tip = (isset($data['team1tip'])) ? $data['team1tip'] : "";
        $this->team2tip = (isset($data['team2tip'])) ? $data['team2tip'] : "";
        $this->start = (isset($data['date_time'])) ? $data['date_time'] : null;
        $this->day = (isset($data['groupid'])) ? $data['groupid'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}