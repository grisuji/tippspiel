<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 27.01.15
 * Time: 18:52
 */

namespace Application\Model;

# manage a single user
class User {
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
            $d = new Day();
        }
        $d->addMatch($match);
        $this->days[$match->day] = $d;
    }

    public function setPoints(){
        $this->points = 0;
        $this->toddde = 0;
        foreach ($this->days as $d) {
            /* @var $d \Application\Model\Day  */
            $d->setPoints();
            $this->points += $d->getPoints();
            $d->setSaisonPoints($this->points);
            $this->toddde += $d->getToddde();
            $d->setSaisonToddde($this->toddde);
        }
    }

    public function getToddde($day){
        $d = $this->days[$day];
        /* @var $d \Application\Model\Day  */
        if (!isset($d)){
            return 0;
        }
        return $d->getSaisonToddde();
    }

    public function getPoints($day){
        $d = $this->days[$day];
        /* @var $d \Application\Model\Day  */
        if (!isset($d)){
            return 0;
        }
        return $d->getSaisonPoints();
    }

    # gives the rank at a special day
    public function getRank($day, $daily_rank){
        $d = $this->days[$day];
        /* @var $d \Application\Model\Day  */
        if (!isset($d)){
            return -1;
        }
        return $d->getRank($daily_rank);
    }

    public function setRank($rank, $day, $daily_rank){
        $d = $this->days[$day];
        /* @var $d \Application\Model\Day  */
        if (isset($d)){
            $d->setRank($rank, $daily_rank);
        }
    }
}