<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 13.03.15
 * Time: 09:20
 */

namespace Application\Model;

class Day {
    public $day;
    private $points = 0;
    private $toddde = 0;
    private $matches = array();

    public function addMatch($match) {
        $matches[$match->id] = $match;
    }

    public function setPoints(){
        $this->points = 0;
        $this->toddde= 0;
        foreach ($this->matches as $m) {
            /* @var $m \Application\Model\Match  */
            $m->setPoints();
            $this->points += $m->getPoints();
            $this->toddde += $m->getToddde();
        }
    }

    public function getToddde(){
        return $this->toddde;
    }

    public function getPoints(){
        return $this->points;
    }
}