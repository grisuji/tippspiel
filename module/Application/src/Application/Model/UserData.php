<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 27.01.15
 * Time: 18:52
 */

namespace Application\Model;
use Zend\Debug\Debug;

# manage a single user
class UserData {
    public $id;
    public $name;
    public $points = 0;
    public $toddde = 0;
    public $days=array();

    function __construct($match){
        /* @var $match \Application\Model\Match  */
        $this->id = $match->userid;
        $this->name = $match->username;
    }

    public function addMatch($match){
        if (!isset($match->day)) {
            throw new \Exception("No day set in match");
        }
        $d = $this->days[$match->day];
        if (!isset($d)) {
            $d = new UserDay($match->day);
        }
        $d->addMatch($match);
        $this->days[$match->day] = $d;
    }

    public function setPoints(){
        $this->points = 0;
        $this->toddde = 0;
        foreach ($this->days as $d) {
            /* @var $d \Application\Model\UserDay  */
            $d->setPoints();
            $this->points += $d->getPoints();
            $d->setSaisonPoints($this->points);
            $this->toddde += $d->getToddde();
            $d->setSaisonToddde($this->toddde);
        }
    }

    public function getToddde($day=0, $daily=true){
        if ($day==0) {
            return $this->toddde;
        }
        $d = $this->days[$day];
        /* @var $d \Application\Model\UserDay  */
        if (!isset($d)){
            return 0;
        }
        return $d->getToddde();
    }

    public function getPoints($day=0, $daily=true){
        if ($day==0) {
            return $this->points;
        }
        $d = $this->days[$day];
        /* @var $d \Application\Model\UserDay  */
        if (!isset($d)){
            return 0;
        }
        return $d->getPoints();
    }

    # gives the rank at a special day
    public function getRank($day, $daily_rank){
        if (!isset($this->days[$day])){
            return -1;
        }
        $d = $this->days[$day];
        /* @var $d \Application\Model\UserDay  */
        return $d->getRank($daily_rank);
    }

    public function setRank($rank, $day, $daily_rank){
        $d = $this->days[$day];
        /* @var $d \Application\Model\UserDay  */
        if (isset($d)){
            $d->setRank($rank, $daily_rank);
        }
    }

    public function getMatchData($day){
        $result = array();
        $d = $this->days[$day];
        /* @var $d \Application\Model\UserDay  */
        if (isset($d)){
            foreach ($d->getMatches() as $m) {
                /* @var $m \Application\Model\Match  */
                $result[$m->id]['tip1'] = $m->team1tip;
                $result[$m->id]['tip2'] = $m->team2tip;
                $result[$m->id]['toddde'] = $m->getToddde();
                $result[$m->id]['points'] = $m->getPoints();
            }
        }
        return $result;
    }
}