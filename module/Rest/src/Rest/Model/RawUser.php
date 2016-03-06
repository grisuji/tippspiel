<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Rest\Model;

use Zend\Debug\Debug;


class RawUser {
    public $id;
    public $name;
    public $email;
    public $avatar;
    public $motto;
    public $registerdate;
    public $lastlogin;
    public $lastchange;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->avatar = (isset($data['avatar'])) ? $data['avatar'] : null;
        $this->motto = (isset($data['motto'])) ? $data['motto'] : null;
        $this->registerdate = (isset($data['registerdate'])) ? $data['registerdate'] : null;
        $this->lastlogin = (isset($data['lastlogin'])) ? $data['lastlogin'] : null;
        $this->lastchange = (isset($data['lastchange'])) ? $data['lastchange'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}