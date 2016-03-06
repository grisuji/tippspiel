<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Rest\Model;

use Zend\Debug\Debug;


class RawTeam {
    public $id;
    public $longname;
    public $shortname;
    public $emblem;
    public $lastchange;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->longname = (isset($data['longname'])) ? $data['longname'] : null;
        $this->shortname = (isset($data['shortname'])) ? $data['shortname'] : null;
        $this->emblem = (isset($data['emblem'])) ? $data['emblem'] : null;
        $this->lastchange = (isset($data['lastchange'])) ? $data['lastchange'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}