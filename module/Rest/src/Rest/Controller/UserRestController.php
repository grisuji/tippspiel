<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;

use Users\Model\User;
use Users\Form\UserEditForm;
use Users\Model\UserTable;
use Zend\View\Model\JsonModel;

class UserRestController extends AbstractRestfulController
{

    protected $userTable;

    public function getList()
    {
        $users = $this->getUserTable()->fetchAll();
        $result = array();
        foreach ($users as $u) {
            $result[] = $u;
        }

        return new JsonModel(array(
            'data' => $result
        ));
    }

    public function get($id)
    {
        $user = $this->getUserTable()->getUser($id);
        return new JsonModel(array(
            'data' => $user,
        ));
    }

    public function create($data)
    {
        Debug::dump("Debug");
        exit;
        # code...
    }

    public function update($id, $data)
    {
        Debug::dump("Debug");
        exit;
        # code...
    }

    public function delete($id)
    {
        Debug::dump("Debug");
        exit;
        # code...
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $this->userTable = $this->getServiceLocator()->get('UserTable');
        }
        return $this->userTable;
    }

}

