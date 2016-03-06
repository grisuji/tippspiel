<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TeamRestController extends AbstractRestfulController
{

    protected $teamTable;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    private function checkAuth()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        if ($headers->has('Authorization')) {
            $base64=$headers->get('Authorization')->getFieldValue();
            $code=base64_decode(str_replace("Basic ","",$base64));
            $login=explode(":",$code,2);
            $user=$login[0];
            $passwd=$login[1];
            $adapter = $this->getAuthService()->getAdapter();
            //check authentication...
            $adapter->setIdentity($user);
            $adapter->setCredential($passwd);
            $result = $this->getAuthService()->authenticate();
            return $result->isValid();
        }
        return false;
    }

    private function getUserId(){
        if($this->getAuthService()->hasIdentity()) {
            return $this->getAuthService()->getStorage()->read()->id;
        }
        return 0;
    }

    private function getRawTeamTable()
    {
        if (!$this->teamTable) {
            $this->teamTable = $this->getServiceLocator()->get('RawTeamTable');
        }
        return $this->teamTable;
    }

    private function genJSon($db_result) {
        $result = array();
        foreach ($db_result as $r) {
            $result[] = $r;
        }

        return new JsonModel(array(
            'data' => $result
        ));
    }

    public function getList()
    {
        if (!$this->checkAuth()) exit;
        $teams = $this->getRawTeamTable()->fetchAll();
        return $this->genJSon($teams);
    }

    public function get($id)
    {
        if (!$this->checkAuth()) exit;
        $teams = $this->getRawTeamTable()->getNewTeams($id);
        return $this->genJSon($teams);
    }

    public function create($data)
    {
        exit;
    }

    public function update($id, $data)
    {
        exit;
    }

    public function delete($id)
    {
        exit;
    }

}

