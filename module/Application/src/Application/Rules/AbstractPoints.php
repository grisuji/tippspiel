<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 23.01.15
 * Time: 21:10
 */

namespace Application\Rules;


abstract class AbstractPoints {
    abstract public function getPoints($result1, $result2, $tip1, $tip2);

    /**
     * @param $result1
     * @param $result2
     * @param $tip1
     * @param $tip2
     * @return bool
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    public function isEqual($result1, $result2, $tip1, $tip2){
        return $result1==$tip1 and $result2==$tip2;
    }

    /**
     * @param $result1
     * @param $result2
     * @param $tip1
     * @param $tip2
     * @return bool
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    protected function haveSameDiff($result1, $result2, $tip1, $tip2){
        return $result2-$result1 == $tip2-$tip1;
    }

    /**
     * @param $result1
     * @param $result2
     * @param $tip1
     * @param $tip2
     * @return bool
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    protected function haveSameSign($result1, $result2, $tip1, $tip2){
        return $this->sign($result2-$result1) == $this->sign($tip2-$tip1);
    }

    /**
     * @param $number
     * @return int
     */
    private function sign($number){
        return $number < 0 ? -1 : ($number > 0 ? 1 : 0);
    }
}