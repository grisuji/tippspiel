<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 30.12.14
 * Time: 12:42
 */

namespace Users\Model;


class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $registerdate;
    public $lastlogin;

    public function setPassword($clear_password)
    {
        $this->password = md5($clear_password);
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        if (isset($data['password']) and !empty($data['password']))
        {
            $this->setPassword($data['password']);
        }
        $this->registerdate = (isset($data['registerdate'])) ? $data['registerdate'] : null;
        $this->lastlogin = (isset($data['lastlogin'])) ? $data['lastlogin'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}