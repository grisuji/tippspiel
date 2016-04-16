<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Rest\Model;

use Zend\Debug\Debug;


class RawMatch {
    public $id;
    public $league;
    public $saison;
    public $groupid;
    public $date_time;
    public $team1id;
    public $team2id;
    public $team1goals;
    public $team2goals;
    public $team1halfgoals;
    public $team2halfgoals;
    public $isfinished;
    public $lastchange;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->league = (isset($data['league'])) ? $data['league'] : null;
        $this->saison = (isset($data['saison'])) ? $data['saison'] : null;
        $this->groupid = (isset($data['groupid'])) ? $data['groupid'] : null;
        $this->date_time = (isset($data['date_time'])) ? $data['date_time'] : null;
        $this->team1id = (isset($data['team1id'])) ? $data['team1id'] : null;
        $this->team2id = (isset($data['team2id'])) ? $data['team2id'] : null;
        $this->team1goals = (isset($data['team1goals']) and $data['team1goals']>=0) ? $data['team1goals'] : -1;
        $this->team2goals = (isset($data['team2goals']) and $data['team2goals']>=0) ? $data['team2goals'] : -1;
        $this->team1halfgoals = (isset($data['team1halfgoals']) and $data['team1halfgoals']>=0) ? $data['team1halfgoals'] : -1;
        $this->team2halfgoals = (isset($data['team2halfgoals']) and $data['team2halfgoals']>=0) ? $data['team2halfgoals'] : -1;
        $this->isfinished = (isset($data['isfinished'])) ? $data['isfinished'] : null;
        $this->lastchange = (isset($data['lastchange'])) ? $data['lastchange'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}