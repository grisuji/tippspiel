<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Rest\Model;

use Zend\Debug\Debug;


class RawTodddeTeamMapping {
    public $toddde_short;
    public $teamid_grisuji;
    public $toddde_long;
    public $lastchange;

    public function exchangeArray($data)
    {
        $this->toddde_short = (isset($data['toddde_short'])) ? $data['toddde_short'] : null;
        $this->teamid_grisuji = (isset($data['teamid_grisuji'])) ? $data['teamid_grisuji'] : null;
        $this->toddde_long = (isset($data['toddde_long'])) ? $data['toddde_long'] : null;
        $this->lastchange = (isset($data['lastchange'])) ? $data['lastchange'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}