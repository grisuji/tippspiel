<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 13.03.15
 * Time: 09:20
 */

namespace Application\Model;
use Zend\Debug\Debug;

class UserDay {
    public $day;
    private $points = 0;
    private $toddde = 0;
    private $saison_points = 0;
    private $saison_toddde = 0;
    private $matches = array();
    private $rank_in_saison = -1;
    private $rank_in_day = -1;


    function __construct($day){
        $this->day = $day;
    }

    public function isMatchSet($match) {
        return isset($this->matches[$match->id]);
    }

    public function addMatch($match) {
        $this->matches[$match->id] = $match;
    }

    public function setPoints($full=true){
        $this->points = 0;
        $this->toddde= 0;
        foreach ($this->matches as $m) {
            /* @var $m \Application\Model\Match  */
            $m->setPoints($full);
            $this->points += $m->getPoints();
            $this->toddde += $m->getToddde();
        }
    }

    public function getToddde($daily=true){
        if (!$daily) {
            return $this->saison_toddde;
        }
        return $this->toddde;
    }

    public function getPoints($daily=true){
        if (!$daily) {
            return $this->saison_points;
        }
        return $this->points;
    }

    public function setSaisonToddde($p){
        $this->saison_toddde = $p;
    }

    public function setSaisonPoints($p){
        $this->saison_points = $p;
    }

    public function setRank($rank, $daily=false){
        if (!$daily){
            $this->rank_in_saison = $rank;
        } else {
            $this->rank_in_day = $rank;
        }
    }

    public function getRank($daily=false){
        if (!$daily){
            return $this->rank_in_saison;
        }
        return $this->rank_in_day;
    }

    public function getMatches() {
        return $this->matches;
    }
}