<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\JsonModel;

class MatchRestController extends AbstractRestfulController
{

    protected $matchTable;

    private function getRawMatchTable()
    {
        if (!$this->matchTable) {
            $this->matchTable = $this->getServiceLocator()->get('RawMatchTable');
        }
        return $this->matchTable;
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
        $matches = $this->getRawMatchTable()->fetchAll();
        return $this->genJSon($matches);
    }

    public function get($id)
    {
        $matches = $this->getRawMatchTable()->getNewMatches($id);
        return $this->genJSon($matches);
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

