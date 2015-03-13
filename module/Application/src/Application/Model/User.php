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
    public $points;
    public $toddde;
    public $days=array();

    function __construct(){

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

    }
}