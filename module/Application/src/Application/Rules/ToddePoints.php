<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 06.02.15
 * Time: 19:05
 */

namespace Application\Rules;


class ToddePoints extends AbstractPoints {
    const EXACT = 6;
    const SAMEDIFF = 4;
    const SAMESIGN = 2;
    const OTHER = 0;
    const MIN = 0;

    public function getPoints($result1, $result2, $tip1, $tip2)
    {
        if ($result1===""  or $result2==="" or $tip1==="" or $tip2===""){
            return 0;
        }
        if ($this->isEqual($result1, $result2, $tip1, $tip2)){
            $points = ToddePoints::EXACT;
        } elseif ($this->haveSameDiff($result1, $result2, $tip1, $tip2)){
            $points = ToddePoints::SAMEDIFF;
        } elseif ($this->haveSameSign($result1, $result2, $tip1, $tip2)){
            $points = ToddePoints::SAMESIGN;
        } else {
            $points = ToddePoints::OTHER;
        }
        return $points;
    }


}