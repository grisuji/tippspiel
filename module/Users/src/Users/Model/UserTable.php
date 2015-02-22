<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 30.12.14
 * Time: 12:54
 */

namespace Users\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Exception;
use Zend\Debug\Debug;


class UserTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveUser(User $user)
    {
        $data = array(
            'email'         => $user->email,
            'name'          => $user->name,
            'motto'         => $user->motto,
        );
        if (isset($user->password) and !empty($user->password)) {
            $data['password'] = $user->password;
        }

        $id = (int)$user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new Exception('User ID does not exist');
            }
        }
    }

    /**
     * Get all Users
     * @throws Exception
     * @return ResultSet
     */
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Get User account by UserId
     * @param string $id
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        debug::dump($row);

        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     * Get the User by name
     * @param string $name
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getUserByName($name)
    {
        $rowset = $this->tableGateway->select(array('name' => $name));
        $row = $rowset->current();
        if (!$row){
            throw new Exception("Could not find user $name");
        }
        return $row;
    }

    /**
     * Delete an User by Id
     * @param string $id
     */
    public function  deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
} 