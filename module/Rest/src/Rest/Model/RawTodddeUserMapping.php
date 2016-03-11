<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Rest\Model;

use Zend\Debug\Debug;


class RawTodddeUserMapping {
    public $toddde_user_name;
    public $grisuji_user_id;
    public $sync_automatic;
    public $lastchange;

    public function exchangeArray($data)
    {
        $this->toddde_user_name = (isset($data['toddde_user_name'])) ? $data['toddde_user_name'] : null;
        $this->grisuji_user_id = (isset($data['grisuji_user_id'])) ? $data['grisuji_user_id'] : null;
        $this->sync_automatic = (isset($data['sync_automatic'])) ? $data['sync_automatic'] : null;
        $this->lastchange = (isset($data['lastchange'])) ? $data['lastchange'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}