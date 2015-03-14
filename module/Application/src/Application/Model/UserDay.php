<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 13.03.15
 * Time: 09:20
 */

namespace Application\Model;

class UserDay {
    public $day;
    private $points = 0;
    private $toddde = 0;
    private $saison_points = 0;
    private $saison_toddde = 0;
    private $matches = array();
    private $rank_in_saison = -1;
    private $rank_in_day = -1;

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

    public function getSaisonToddde(){
        return $this->saison_toddde;
    }

    public function getSaisonPoints(){
        return $this->saison_points;
    }

    public function setSaisonToddde($p){
        $this->saison_toddde = $p;
    }

    public function setSaisonPoints($p){
        $this->saison_points = $p;
    }

    public function setRank($rank, $day=false){
        if (!$day){
            $this->rank_in_saison = $rank;
        } else {
            $this->rank_in_day = $rank;
        }
    }

    public function getRank($day=false){
        if (!$day){
            return $this->rank_in_saison;
        }
        return $this->rank_in_day;
    }

    public function getMatches() {
        return $this->matches;
    }
}