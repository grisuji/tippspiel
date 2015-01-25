<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 23.01.15
 * Time: 21:34
 */

namespace Application\Model;

class GrisujiPoints extends AbstractPoints {
    const EXACT = 8;
    const SAMEDIFF = 8;
    const SAMESIGN = 8;
    const OTHER = 6;
    const MIN = 0;

    /**
     * @param $result1
     * @param $result2
     * @param $tip1
     * @param $tip2
     * @return mixed
     */
    public function getPoints($result1, $result2, $tip1, $tip2)
    {
        if ($result1===""  or $result2==="" or $tip1==="" or $tip2===""){
            return 0;
        }
        if ($this->isEqual($result1, $result2, $tip1, $tip2)){
            $points = GrisujiPoints::EXACT;
        } elseif ($this->haveSameDiff($result1, $result2, $tip1, $tip2)){
            $points = GrisujiPoints::SAMEDIFF;
        } elseif ($this->haveSameSign($result1, $result2, $tip1, $tip2)){
            $points = GrisujiPoints::SAMESIGN;
        } else {
            $points = GrisujiPoints::OTHER;
        }
        return (max(GrisujiPoints::MIN, $points-(
                $this->diff($result1-$tip1)+
                $this->diff($result2-$tip2)+
                2*$this->diff(($result2-$result1)-($tip2-$tip1))
            )));
    }

    /**
     * @param $number
     * @return number
     */
    private function diff($number)
    {
        return abs($number);
    }
}