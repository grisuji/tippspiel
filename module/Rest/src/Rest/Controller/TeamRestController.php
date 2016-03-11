<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TeamRestController extends AbstractRestfulController
{

    protected $teamTable;

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
        $teams = $this->getRawTeamTable()->fetchAll();
        return $this->genJSon($teams);
    }

    public function get($id)
    {
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

