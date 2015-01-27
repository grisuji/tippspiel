<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 27.01.15
 * Time: 18:52
 */

namespace Application\Model;


class User {
    public $id;
    public $name;
    public $points;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->points = (isset($data['points'])) ? $data['points'] : 0;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}