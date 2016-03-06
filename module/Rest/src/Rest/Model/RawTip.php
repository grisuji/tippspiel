<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 09:15
 */
namespace Rest\Model;

class RawTip
{
    public $id;
    public $userid;
    public $matchid;
    public $team1tip;
    public $team2tip;
    public $lastchange;


    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->userid = (isset($data['userid'])) ? $data['userid'] : null;
        $this->matchid = (isset($data['matchid'])) ? $data['matchid'] : null;
        $this->team1tip = (isset($data['team1tip'])) ? $data['team1tip'] : null;
        $this->team2tip = (isset($data['team2tip'])) ? $data['team2tip'] : null;
        $this->lastchange = (isset($data['lastchange'])) ? $data['lastchange'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
